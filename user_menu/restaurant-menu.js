const popup = document.getElementById("foodPopup");
const popupFoodImage = document.getElementById("popupFoodImage");
const popupFoodName = document.getElementById("popupFoodName");
const popupFoodPrice = document.getElementById("popupFoodPrice");
const foodQuantity = document.getElementById("foodQuantity");

function showPopup(foodName, foodImage, foodPrice) {
    popup.style.display = "block";
    popupFoodName.textContent = foodName;
    popupFoodImage.src = foodImage;
    popupFoodPrice.textContent = `${foodPrice} Tk`;
    foodQuantity.value = 1;
}

function hidePopup() {
    popup.style.display = "none";
}

function decreaseQuantity() {
    if (foodQuantity.value > 1) {
        foodQuantity.value--;
    }
}

function increaseQuantity() {
    foodQuantity.value++;
}

function addToCart() {
    alert("Item added to cart!");
    hidePopup();
    window.location.href = "restaurant-menu.php";
}

window.onclick = function (event) {
    if (event.target === popup) {
        hidePopup();
    }
};
