
document.querySelectorAll('[data-lang]').forEach(btn=>{
btn.addEventListener('click',()=>{
alert("Taal gekozen: " + btn.dataset.lang);
});
});
