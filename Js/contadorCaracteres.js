function updateCharCount(textarea) {
    const maxLength = textarea.getAttribute('maxlength');
    const currentLength = textarea.value.length;
    document.getElementById('charCount').textContent = `${currentLength}/${maxLength}`;
}
