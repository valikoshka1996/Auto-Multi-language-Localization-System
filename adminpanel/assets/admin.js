document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.editable-value').forEach(input => {
        input.addEventListener('click', function () {
            const currentVal = this.value;
            const textarea = document.createElement('textarea');
            textarea.className = 'form-control';
            textarea.name = 'val[]';
            textarea.value = currentVal;
            textarea.style.minHeight = '100px';

            // Replace input with textarea
            this.replaceWith(textarea);
            textarea.focus();

            // When losing focus, revert to input
            textarea.addEventListener('blur', function () {
                const newVal = this.value;
                const newInput = document.createElement('input');
                newInput.className = 'form-control editable-value';
                newInput.name = 'val[]';
                newInput.value = newVal;

                this.replaceWith(newInput);
                // Re-attach click listener
                newInput.addEventListener('click', arguments.callee);
            });
        });
    });
});