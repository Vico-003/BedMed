document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".select-btn");
    const popup = document.getElementById("popup");
    const confirmCancel = document.getElementById("confirmCancel");
    const cancelPopup = document.getElementById("cancelPopup");

    let selectedId = localStorage.getItem("forfait") || "serenite";
    let tempCancelId = null;

    buttons.forEach(btn => {
        const card = btn.closest(".card");
        const id = card.dataset.id;

        if (id === selectedId) {
            card.classList.add("selected");
            if (id === "serenite") {
                btn.textContent = "Forfait activé";
                btn.classList.add("btn-disabled");
                btn.disabled = true;
            } else {
                btn.textContent = "Annuler";
                btn.classList.replace("btn-select", "btn-cancel");
            }
        }

        btn.addEventListener("click", () => {
            if (id === "serenite") return;

            if (selectedId === id) {
                tempCancelId = id;
                popup.style.display = "flex";
            } else {
                localStorage.setItem("forfait", id);
                updateButtons(id);
            }
        });
    });

    function updateButtons(id) {
        buttons.forEach(btn => {
            const card = btn.closest(".card");
            const cid = card.dataset.id;

            card.classList.remove("selected");

            if (cid === id) {
                card.classList.add("selected");
                if (cid === "serenite") {
                    btn.textContent = "Forfait activé";
                    btn.classList.add("btn-disabled");
                    btn.disabled = true;
                    btn.classList.remove("btn-select", "btn-cancel");
                } else {
                    btn.textContent = "Annuler";
                    btn.disabled = false;
                    btn.classList.remove("btn-disabled");
                    btn.classList.replace("btn-select", "btn-cancel");
                }
            } else {
                if (cid === "serenite") {
                    btn.textContent = "Sélectionner";
                    btn.classList.remove("btn-disabled");
                    btn.classList.add("btn-select");
                    btn.disabled = false;
                } else {
                    btn.textContent = "Sélectionner";
                    btn.classList.remove("btn-cancel");
                    btn.classList.add("btn-select");
                    btn.disabled = false;
                }
            }
        });
        selectedId = id;
    }

    confirmCancel.addEventListener("click", () => {
        localStorage.setItem("forfait", "serenite");
        updateButtons("serenite");
        popup.style.display = "none";
        tempCancelId = null;
    });

    cancelPopup.addEventListener("click", () => {
        popup.style.display = "none";
        tempCancelId = null;
    });
});
