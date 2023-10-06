
const children = document.querySelectorAll(".fct-rotation-group-inner > *");
const randomIndex = Math.floor( Math.random() * children.length );

children.forEach((child, index) => {
    index === randomIndex && child.classList.add("fct-rotate");
});