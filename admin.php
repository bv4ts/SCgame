<?php /* admin.php */ ?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IAU Syrian Community - Create Game</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="topbar">
  <div class="brand">الجالية السورية</div>
  <nav>
    <a href="index.php">اللعبة</a>
    <a class="active" href="admin.php">إدارة اللعبة</a>
    <a href="links.php">الروابط</a>
  </nav>
</header>

<section class="admin">
  <div class="card">
    <h3>أضف قسم :</h3>
    <div class="row" style="margin-bottom:16px">
      <input id="newCatName" type="text" placeholder="مثال: ثقافي" onkeypress="if(event.key==='Enter') addCategory()">
      <button class="btn btn-green" onclick="addCategory()">+</button>
    </div>
    <div id="catList" class="list"></div>
  </div>

  <div class="card">
    <h3>أضف الأسئلة :</h3>
    <div id="selectedCatInfo" style="margin-bottom:16px;padding:12px;background:#f5f5f5;border-radius:10px;text-align:center;font-weight:600;color:#3d7f38;">
      الرجاء اختيار قسم من القائمة اليمنى
    </div>
    <div class="row" style="margin-bottom:16px">
      <input id="newQ" type="text" placeholder="اكتب السؤال هنا" onkeypress="if(event.key==='Enter') addQuestion()">
      <button class="btn btn-green" onclick="addQuestion()">+</button>
    </div>
    <div id="qList" class="list"></div>
  </div>
</section>

<script>
let cats = [];
let currentCatId = null;

// توليد لون عشوائي
function getRandomColor() {
  const colors = [
    '#E53935', '#D32F2F', '#C62828', // أحمر
    '#8E24AA', '#7B1FA2', '#6A1B9A', // بنفسجي
    '#5E35B1', '#512DA8', '#4527A0', // بنفسجي غامق
    '#3949AB', '#303F9F', '#283593', // أزرق غامق
    '#1E88E5', '#1976D2', '#1565C0', // أزرق
    '#039BE5', '#0288D1', '#0277BD', // أزرق فاتح
    '#43A047', '#388E3C', '#2E7D32', // أخضر
    '#FB8C00', '#F57C00', '#EF6C00', // برتقالي
    '#F4511E', '#E64A19', '#D84315', // برتقالي محمر
    '#6D4C41', '#5D4037', '#4E342E'  // بني
  ];
  return colors[Math.floor(Math.random() * colors.length)];
}

async function refreshCats() {
  try {
    const res = await fetch('api/categories.php');
    cats = await res.json();
    const catList = document.getElementById('catList');
    catList.innerHTML = '';
    
    cats.forEach(c => {
      // قائمة الأقسام
      const li = document.createElement('div');
      li.className = 'item';
      li.innerHTML = `<span style="display:flex;align-items:center;gap:8px;">
          <input type="color" id="catColor-${c.id}" value="${c.color}" 
                 style="width:30px;height:30px;border:2px solid #ddd;border-radius:6px;cursor:pointer;"
                 onchange="updateCatColor(${c.id}, this.value)"
                 title="تغيير اللون">
          <input type="text" id="catName-${c.id}" value="${c.name}" 
                 style="border:none;background:transparent;font-size:15px;font-weight:500;width:150px;font-family:'Tajawal',Arial,sans-serif;"
                 onblur="updateCatName(${c.id}, this.value, document.getElementById('catColor-${c.id}').value)"
                 onkeypress="if(event.key==='Enter') this.blur()">
        </span>
        <span class="row">
          <button class="btn btn-green" onclick="selectCat(${c.id}, '${c.name}')" title="إدارة الأسئلة">➕</button>
          <button class="btn btn-red" onclick="delCat(${c.id})" title="حذف القسم">🗑️</button>
        </span>`;
      catList.appendChild(li);
    });
    
    if (cats.length && !currentCatId) { 
      currentCatId = cats[0].id; 
    }
  } catch (error) {
    console.error('Error refreshing categories:', error);
  }
}

async function addCategory() {
  const name = document.getElementById('newCatName').value.trim();
  const color = getRandomColor(); // لون عشوائي تلقائياً
  if (!name) {
    alert('الرجاء إدخال اسم القسم');
    return;
  }
  try {
    await fetch('api/categories.php', {
      method: 'POST', 
      body: new URLSearchParams({name, color})
    });
    document.getElementById('newCatName').value = '';
    refreshCats();
  } catch (error) {
    console.error('Error adding category:', error);
  }
}

async function delCat(id) {
  try {
    const response = await fetch('api/categories.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams({_action: 'delete', id: id}).toString()
    });
    const result = await response.json();
    console.log('Delete result:', result);
    
    if (currentCatId == id) {
      currentCatId = null;
      document.getElementById('selectedCatInfo').innerHTML = 'الرجاء اختيار قسم من القائمة اليمنى';
      document.getElementById('qList').innerHTML = '';
    }
    refreshCats();
  } catch (error) {
    console.error('Error deleting category:', error);
    alert('خطأ في الحذف: ' + error.message);
  }
}

async function updateCatName(id, name, color) {
  name = name.trim();
  if (!name) {
    refreshCats();
    return;
  }
  const cat = cats.find(c => c.id === id);
  if (cat && name !== cat.name) {
    try {
      const response = await fetch('api/categories.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({_action: 'update', id, name, color})
      });
      const result = await response.json();
      console.log('Update category result:', result);
      
      if (result.ok) {
        cat.name = name;
        if (currentCatId === id) {
          document.getElementById('selectedCatInfo').innerHTML = `القسم المحدد: <strong>${name}</strong>`;
        }
      } else {
        console.error('Update failed:', result);
        alert('خطأ في التعديل: ' + (result.error || 'Unknown error'));
      }
    } catch (error) {
      console.error('Error updating category:', error);
      alert('خطأ في التعديل: ' + error.message);
      refreshCats();
    }
  }
}

async function updateCatColor(id, color) {
  const cat = cats.find(c => c.id === id);
  if (!cat) return;
  try {
    const response = await fetch('api/categories.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: new URLSearchParams({_action: 'update', id, name: cat.name, color})
    });
    const result = await response.json();
    console.log('Update color result:', result);
    
    if (result.ok) {
      cat.color = color;
    } else {
      console.error('Color update failed:', result);
      alert('خطأ في تعديل اللون: ' + (result.error || 'Unknown error'));
    }
  } catch (error) {
    console.error('Error updating color:', error);
    alert('خطأ في تعديل اللون: ' + error.message);
  }
}

function selectCat(id, name) { 
  currentCatId = id; 
  document.getElementById('selectedCatInfo').innerHTML = `القسم المحدد: <strong>${name}</strong>`;
  loadQuestions(id); 
}

async function loadQuestions(cid) {
  try {
    const res = await fetch('api/questions.php?category_id=' + cid);
    const items = await res.json();
    const list = document.getElementById('qList');
    list.innerHTML = '';
    
    if (items.length === 0) {
      list.innerHTML = '<div style="text-align:center;padding:20px;color:#666;">لا توجد أسئلة حتى الآن</div>';
      return;
    }
    
    items.forEach(q => {
      const li = document.createElement('div');
      li.className = 'item';
      li.innerHTML = `<input type="text" id="qBody-${q.id}" value="${q.body.replace(/"/g, '&quot;')}" 
                             style="flex:1;border:none;background:transparent;font-size:15px;padding:8px;font-family:'Tajawal',Arial,sans-serif;"
                             onblur="updateQuestion(${q.id}, this.value)"
                             onkeypress="if(event.key==='Enter') this.blur()">
        <button class="btn btn-red" onclick="delQ(${q.id})" title="حذف السؤال">🗑️</button>`;
      list.appendChild(li);
    });
  } catch (error) {
    console.error('Error loading questions:', error);
  }
}

async function addQuestion() {
  const body = document.getElementById('newQ').value.trim();
  const cid = currentCatId;
  if (!cid) {
    alert('الرجاء اختيار قسم أولاً من القائمة اليمنى');
    return;
  }
  if (!body) {
    alert('الرجاء إدخال نص السؤال');
    return;
  }
  try {
    await fetch('api/questions.php', {
      method: 'POST', 
      body: new URLSearchParams({category_id: cid, body})
    });
    document.getElementById('newQ').value = '';
    loadQuestions(cid);
  } catch (error) {
    console.error('Error adding question:', error);
  }
}

async function delQ(id) {
  try {
    const response = await fetch('api/questions.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams({_action: 'delete', id: id}).toString()
    });
    const result = await response.json();
    console.log('Delete question result:', result);
    loadQuestions(currentCatId);
  } catch (error) {
    console.error('Error deleting question:', error);
    alert('خطأ في حذف السؤال: ' + error.message);
  }
}

async function updateQuestion(id, body) {
  body = body.trim();
  if (!body) {
    loadQuestions(currentCatId);
    return;
  }
  try {
    const response = await fetch('api/questions.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: new URLSearchParams({_action: 'update', id, body})
    });
    const result = await response.json();
    console.log('Update question result:', result);
    
    if (!result.ok) {
      console.error('Question update failed:', result);
      alert('خطأ في تعديل السؤال: ' + (result.error || 'Unknown error'));
    }
  } catch (error) {
    console.error('Error updating question:', error);
    alert('خطأ في تعديل السؤال: ' + error.message);
    loadQuestions(currentCatId);
  }
}

// تحميل الأقسام عند فتح الصفحة
refreshCats();
</script>
</body>
</html>
