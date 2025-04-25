// Закриття по кліку поза прапорами
document.getElementById('languageModal').addEventListener('click', function(e) {
  if (e.target === this) {
    this.style.display = 'none';
  }
});