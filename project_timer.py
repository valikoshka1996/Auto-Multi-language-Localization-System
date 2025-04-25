import os
from datetime import datetime, timedelta
from collections import defaultdict

# === ПАРАМЕТРИ ===
SESSION_TIMEOUT_MINUTES = 30  # розрив між змінами щоб рахувати як нову сесію
MIN_SESSION_DURATION = timedelta(minutes=15)
MAX_SESSION_DURATION = timedelta(hours=2)
IMPORTANT_EXTENSIONS = ['.py', '.php', '.html', '.css', '.js', '.sql', '.txt', '.md']

# === ДОПОМІЖНІ ===
def is_important_file(filename):
    return any(filename.lower().endswith(ext) for ext in IMPORTANT_EXTENSIONS)

def collect_modification_times(root_dir):
    mtimes = []
    file_info = []
    for dirpath, _, filenames in os.walk(root_dir):
        for filename in filenames:
            if not is_important_file(filename):
                continue
            try:
                full_path = os.path.join(dirpath, filename)
                stat = os.stat(full_path)
                mtime = datetime.fromtimestamp(stat.st_mtime)
                ctime = datetime.fromtimestamp(stat.st_ctime)
                mtimes.append(mtime)
                file_info.append((full_path, ctime, mtime))
            except Exception as e:
                print(f"[!] Error reading {filename}: {e}")
    return sorted(mtimes), file_info

def group_into_sessions(times, timeout_minutes):
    if not times:
        return []
    sessions = []
    current_session = [times[0]]
    for t in times[1:]:
        if (t - current_session[-1]).total_seconds() <= timeout_minutes * 60:
            current_session.append(t)
        else:
            sessions.append(current_session)
            current_session = [t]
    sessions.append(current_session)
    return sessions

def estimate_session_duration(session):
    if not session:
        return timedelta()
    actual_duration = session[-1] - session[0]
    if actual_duration < MIN_SESSION_DURATION:
        return MIN_SESSION_DURATION
    elif actual_duration > MAX_SESSION_DURATION:
        return MAX_SESSION_DURATION
    return actual_duration

def analyze_project(path):
    mtimes, file_info = collect_modification_times(path)
    sessions = group_into_sessions(mtimes, SESSION_TIMEOUT_MINUTES)

    raw_total = timedelta()
    days_active = defaultdict(list)
    detailed_sessions = []

    for session in sessions:
        duration = estimate_session_duration(session)
        raw_total += duration
        session_day = session[0].date()
        days_active[session_day].append(duration)
        detailed_sessions.append({
            'start': session[0],
            'end': session[-1],
            'duration': duration,
            'activity_count': len(session)
        })

    refined_total = timedelta()
    for day, durations in days_active.items():
        base = sum(durations, timedelta())
        if len(durations) >= 3:
            base *= 1.3  # контекстне навантаження (перемикання уваги)
        refined_total += base

    return {
        'total_time': refined_total,
        'raw_time': raw_total,
        'days_active': sorted(days_active.keys()),
        'session_count': len(sessions),
        'sessions': detailed_sessions,
        'file_info': file_info
    }

def write_report(report, output_file='work_analysis.txt'):
    with open(output_file, 'w', encoding='utf-8') as f:
        f.write("📊 PROJECT WORK ANALYSIS REPORT\n")
        f.write("="*45 + "\n\n")
        f.write(f"🔹 Refined (realistic) time: {report['total_time']}\n")
        f.write(f"🔸 Raw (summed) time: {report['raw_time']}\n")
        f.write(f"📅 Active days: {len(report['days_active'])}\n")
        f.write(f"🕒 Work sessions: {report['session_count']}\n\n")

        f.write("📆 Days with activity:\n")
        for d in report['days_active']:
            f.write(f"- {d.strftime('%Y-%m-%d')}\n")
        f.write("\n")

        f.write("🧠 Work Sessions:\n")
        for i, s in enumerate(report['sessions'], 1):
            f.write(f"{i:02}. {s['start']} → {s['end']} | Duration: {s['duration']} | Events: {s['activity_count']}\n")
        f.write("\n")

        f.write("📁 Files scanned:\n")
        for path, ctime, mtime in report['file_info']:
            f.write(f"{path}\n  Created:  {ctime.strftime('%Y-%m-%d %H:%M:%S')}\n  Modified: {mtime.strftime('%Y-%m-%d %H:%M:%S')}\n\n")

    print(f"\n✅ Звіт збережено як: {output_file}")

# === ЗАПУСК ===
if __name__ == "__main__":
    folder = input("📂 Введи шлях до папки з проєктом: ").strip()
    if not os.path.isdir(folder):
        print("❌ Це не існуюча директорія.")
    else:
        result = analyze_project(folder)
        write_report(result)
