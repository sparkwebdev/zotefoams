
document.addEventListener('DOMContentLoaded', function() {
  // Add a click event listener to all elements with the data-clickable-url attribute
  document.querySelectorAll('[data-clickable-url]').forEach(function(article) {
      var url = article.getAttribute('data-clickable-url');
      if (url) {
          article.addEventListener('click', function() {
              window.location.href = url;
          });
      }
  });
});

