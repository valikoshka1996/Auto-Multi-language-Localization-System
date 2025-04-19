document.addEventListener("DOMContentLoaded", function () {
    // Делегування замість призначення обробників на кожен input
    document.getElementById('entries').addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('editable-value')) {
            const input = e.target;

            // Уникаємо повторного натискання
            if (input.dataset.editing === "true") return;

            input.dataset.editing = "true";

            const textarea = document.createElement('textarea');
            textarea.className = 'form-control editable-value';
            textarea.name = 'val[]';
            textarea.value = input.value;
            textarea.style.minHeight = '100px';

            input.replaceWith(textarea);
            textarea.focus();

            textarea.addEventListener('blur', function () {
                const newInput = document.createElement('input');
                newInput.className = 'form-control editable-value';
                newInput.name = 'val[]';
                newInput.value = textarea.value;
                newInput.removeAttribute('data-editing');

                textarea.replaceWith(newInput);
            });
        }
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

