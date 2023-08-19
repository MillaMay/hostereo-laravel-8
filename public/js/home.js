document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('copy-link').addEventListener('click', function() {
        var link = document.createElement('input');
        link.value = window.location.href;
        document.body.appendChild(link);
        link.select();
        document.execCommand('copy');
        document.body.removeChild(link);

        alert('Link successfully copied to clipboard!');
    });
});
