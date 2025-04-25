document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('entries').addEventListener('click', function (e) {
        const target = e.target;

        // Якщо натиснули НЕ на input, або це вже textarea — нічого не робимо
        if (!target.classList.contains('editable-value') || target.tagName === 'TEXTAREA') return;

        // Уникаємо повторного натискання
        if (target.dataset.editing === "true") return;

        target.dataset.editing = "true";

        const textarea = document.createElement('textarea');
        textarea.className = 'form-control editable-value';
        textarea.name = 'val[]';
        textarea.value = target.value;
        textarea.style.minHeight = '100px';

        target.replaceWith(textarea);
        textarea.focus();

        textarea.addEventListener('blur', function () {
            const newInput = document.createElement('input');
            newInput.className = 'form-control editable-value';
            newInput.name = 'val[]';
            newInput.value = textarea.value;
            newInput.removeAttribute('data-editing');

            textarea.replaceWith(newInput);
        });
    });
});


  document.addEventListener("DOMContentLoaded", function () {
    const scrollTopBtn = document.getElementById("scrollTopBtn");

    window.addEventListener("scroll", () => {
      if (window.scrollY > 100) {
        scrollTopBtn.style.display = "block";
      } else {
        scrollTopBtn.style.display = "none";
      }
    });

    scrollTopBtn.addEventListener("click", () => {
      window.scrollTo({
        top: 0,
        behavior: "smooth"
      });
    });
  });
  
  document.addEventListener("DOMContentLoaded", function () {
    const toggleEdit = document.getElementById("toggleEditKeys");

    function updateKeyInputsState() {
      const keyInputs = document.querySelectorAll('input[name="key[]"]');
      keyInputs.forEach(input => {
        input.disabled = !toggleEdit.checked;
      });
    }

    toggleEdit.addEventListener("change", updateKeyInputsState);
    updateKeyInputsState(); // виклик одразу при завантаженні
  });

document.addEventListener("DOMContentLoaded", () => {
    const keyInput = document.getElementById("filterKey");
    const valueInput = document.getElementById("filterValue");

    if (!keyInput || !valueInput) return; // Безпечна перевірка

    function filterTable() {
        const keyFilter = keyInput.value.toLowerCase();
        const valueFilter = valueInput.value.toLowerCase();

        document.querySelectorAll("#entries tr").forEach(row => {
            const key = row.querySelector('input[name="key[]"]').value.toLowerCase();
            const val = row.querySelector('input[name="val[]"]').value.toLowerCase();

            const show = key.includes(keyFilter) && val.includes(valueFilter);
            row.style.display = show ? "" : "none";
        });
    }

    keyInput.addEventListener("input", filterTable);
    valueInput.addEventListener("input", filterTable);
});
