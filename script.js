// ========== SECTION HOVER EFFECTS ==========
document.addEventListener("DOMContentLoaded", () => {
  // Sélectionne tous les éléments de texte
  const textes = document.querySelectorAll("p, li, h1, h2, h3, h4, strong, em, a, figcaption, span");
  
  textes.forEach((el, index) => {
    // Sauvegarde le display original
    const originalDisplay = window.getComputedStyle(el).display;
    
    // Force inline-block seulement si c'est inline
    if (originalDisplay === 'inline') {
      el.style.display = "inline-block";
    }
    
    // Applique les styles de base
    el.style.transition = "transform 0.3s ease-in-out, background-color 0.2s";
    el.style.transformOrigin = "center";
    el.style.cursor = "pointer";
    
    el.addEventListener("mouseenter", () => {
      el.style.transform = "scale(1.1)";
      el.style.zIndex = "999";
      el.style.position = "relative";
    });
    
    el.addEventListener("mouseleave", () => {
      el.style.transform = "scale(1)";
      el.style.zIndex = "auto";
      el.style.backgroundColor = "transparent";
    });
  });
});
