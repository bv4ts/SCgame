// assets/js/wheel.js
const cvs = document.getElementById('wheel');
const ctx = cvs.getContext('2d');

let categories = [];    // [{id, name, color}]
let sliceAngle = 0;

async function loadCategories() {
  try {
    const res = await fetch('api/categories.php');
    categories = await res.json();
    if (categories.length === 0) {
      // إضافة أقسام افتراضية للعرض
      categories = [
        {id: 0, name: '1', color: '#E53935'},
        {id: 0, name: '2', color: '#8E24AA'},
        {id: 0, name: '3', color: '#A1887F'},
        {id: 0, name: '4', color: '#EC407A'},
        {id: 0, name: '5', color: '#757575'},
        {id: 0, name: '6', color: '#1E88E5'},
        {id: 0, name: '7', color: '#FB8C00'},
        {id: 0, name: '8', color: '#43A047'}
      ];
    }
    drawWheel();
  } catch (error) {
    console.error('Error loading categories:', error);
  }
}

function drawWheel() {
  const R = cvs.width / 2;
  const cx = R, cy = R;
  const n = categories.length || 8;
  sliceAngle = (2 * Math.PI) / n;

  ctx.clearRect(0, 0, cvs.width, cvs.height);
  
  for (let i = 0; i < n; i++) {
    const start = i * sliceAngle;
    const end = start + sliceAngle;
    
    ctx.beginPath();
    ctx.moveTo(cx, cy);
    ctx.arc(cx, cy, R, start, end);
    ctx.closePath();
    ctx.fillStyle = categories[i]?.color || defaultColor(i);
    ctx.fill();
    
    // إضافة حدود بيضاء بين الشرائح
    ctx.strokeStyle = '#fff';
    ctx.lineWidth = 3;
    ctx.stroke();

    // رقم/اسم القسم
    ctx.save();
    ctx.translate(cx, cy);
    ctx.rotate(start + sliceAngle / 2);
    ctx.textAlign = 'center';
    ctx.fillStyle = '#fff';
    ctx.font = 'bold 28px Arial';
    ctx.shadowColor = 'rgba(0,0,0,0.5)';
    ctx.shadowBlur = 4;
    ctx.fillText(categories[i]?.name || (i + 1).toString(), R * 0.65, 10);
    ctx.restore();
  }
  
  // رسم دائرة بيضاء في المركز
  ctx.beginPath();
  ctx.arc(cx, cy, 30, 0, 2 * Math.PI);
  ctx.fillStyle = '#fff';
  ctx.fill();
  ctx.strokeStyle = '#ddd';
  ctx.lineWidth = 2;
  ctx.stroke();
}

// ألوان افتراضية متباينة
function defaultColor(i) {
  const palette = ['#E53935', '#8E24AA', '#5E35B1', '#3949AB', '#1E88E5', '#039BE5', '#43A047', '#FB8C00'];
  return palette[i % palette.length];
}

let spinning = false;
cvs.addEventListener('click', async () => {
  if (spinning || categories.length === 0) return;
  if (categories[0].id === 0) {
    alert('الرجاء إضافة أقسام من صفحة CREATE GAME أولاً');
    return;
  }
  
  spinning = true;

  // دوران عشوائي + إبطاء
  const spins = 5 + Math.random() * 3;
  const final = Math.random() * 2 * Math.PI;
  const total = spins * 2 * Math.PI + final;

  const startTime = performance.now();
  const duration = 3500; // ms

  const animate = (t) => {
    const p = Math.min(1, (t - startTime) / duration);
    // ease-out cubic
    const eased = 1 - Math.pow(1 - p, 3);
    const angle = eased * total;
    
    // رسم مع دوران
    ctx.save();
    ctx.translate(cvs.width / 2, cvs.height / 2);
    ctx.rotate(angle);
    ctx.translate(-cvs.width / 2, -cvs.height / 2);
    drawWheel();
    ctx.restore();

    if (p < 1) {
      requestAnimationFrame(animate);
    } else { 
      spinning = false; 
      onStop(angle); 
    }
  };
  requestAnimationFrame(animate);
});

async function onStop(angle) {
  // السهم في الأعلى (زاوية 0)، حدد الشريحة عند الزاوية المعاكسة للدوران
  const n = categories.length;
  const pos = (2 * Math.PI - (angle % (2 * Math.PI)));
  const index = Math.floor(pos / sliceAngle) % n;
  const chosen = categories[index];

  try {
    // اجلب سؤالًا عشوائيًا لهذا القسم
    const res = await fetch(`api/spin.php?category_id=${chosen.id}`);
    const q = await res.json();
    document.querySelector('.modal-title').textContent = `سؤال ${chosen.name}`;
    document.getElementById('questionText').textContent = q.body;
    openModal();
  } catch (error) {
    console.error('Error fetching question:', error);
    document.querySelector('.modal-title').textContent = `سؤال ${chosen.name}`;
    document.getElementById('questionText').textContent = 'حدث خطأ في تحميل السؤال';
    openModal();
  }
}

// تحميل الأقسام عند فتح الصفحة
loadCategories();
