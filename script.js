const resultItems = document.querySelectorAll(".result-item");

resultItems.forEach(resultItem => {
    resultItem.addEventListener('click', () => {
        const dishItems = resultItem.querySelectorAll(".dishes");
        dishItems.forEach(dishItem => {
            if (dishItem.style.display === "block") {
                dishItem.style.display = "none";
            } else {
                dishItem.style.display = "block";
            }
        });
    });
});