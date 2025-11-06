// assets/js/ui.js
function openModal() { 
  document.getElementById('questionModal').classList.remove('hidden'); 
}

function closeModal() { 
  document.getElementById('questionModal').classList.add('hidden'); 
}

// تأثيرات إضافية للواجهة
document.addEventListener('DOMContentLoaded', () => {
  // إضافة تأثير الضغط على الأزرار
  document.querySelectorAll('.btn').forEach(btn => {
    btn.addEventListener('click', function() {
      this.style.transform = 'scale(0.95)';
      setTimeout(() => {
        this.style.transform = '';
      }, 100);
    });
  });
});
