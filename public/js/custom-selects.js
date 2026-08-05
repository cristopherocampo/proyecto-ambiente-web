document.addEventListener("DOMContentLoaded", () => {
    const closeAll = (except = null) => {
        document.querySelectorAll(".custom-select.is-open").forEach((item) => {
            if (item !== except) {
                item.classList.remove("is-open");
                item.querySelector(".custom-select-trigger").setAttribute("aria-expanded", "false");
            }
        });
    };

    document.querySelectorAll("select:not([data-custom-ready])").forEach((select, selectIndex) => {
        select.dataset.customReady = "true";
        select.classList.add("select-native-accessible");

        const wrapper = document.createElement("div");
        wrapper.className = "custom-select";
        if (select.closest(".inline")) wrapper.classList.add("custom-select-compact");

        const trigger = document.createElement("button");
        trigger.type = "button";
        trigger.className = "custom-select-trigger";
        trigger.setAttribute("aria-haspopup", "listbox");
        trigger.setAttribute("aria-expanded", "false");

        const value = document.createElement("span");
        value.className = "custom-select-value";
        const arrow = document.createElement("span");
        arrow.className = "custom-select-arrow";
        arrow.setAttribute("aria-hidden", "true");
        trigger.append(value, arrow);

        const menu = document.createElement("div");
        menu.className = "custom-select-menu";
        menu.id = `custom-select-menu-${selectIndex}`;
        menu.setAttribute("role", "listbox");
        trigger.setAttribute("aria-controls", menu.id);

        const sync = () => {
            const selected = select.options[select.selectedIndex];
            value.textContent = selected ? selected.textContent : "Selecciona una opción";
            trigger.classList.toggle("is-placeholder", !select.value);
            menu.querySelectorAll(".custom-select-option").forEach((option) => {
                const active = option.dataset.value === select.value;
                option.classList.toggle("is-selected", active);
                option.setAttribute("aria-selected", active ? "true" : "false");
            });
        };

        Array.from(select.options).forEach((nativeOption) => {
            const option = document.createElement("button");
            option.type = "button";
            option.className = "custom-select-option";
            option.dataset.value = nativeOption.value;
            option.textContent = nativeOption.textContent;
            option.setAttribute("role", "option");
            option.disabled = nativeOption.disabled;
            option.addEventListener("click", () => {
                select.value = nativeOption.value;
                sync();
                closeAll();
                trigger.focus();
                select.dispatchEvent(new Event("input", { bubbles: true }));
                select.dispatchEvent(new Event("change", { bubbles: true }));
            });
            menu.appendChild(option);
        });

        trigger.addEventListener("click", () => {
            const opening = !wrapper.classList.contains("is-open");
            closeAll(wrapper);
            wrapper.classList.toggle("is-open", opening);
            trigger.setAttribute("aria-expanded", opening ? "true" : "false");
            if (opening) menu.querySelector(".is-selected:not(:disabled)")?.focus();
        });
        wrapper.addEventListener("keydown", (event) => {
            if (event.key === "Escape") {
                closeAll();
                trigger.focus();
            }
        });
        select.addEventListener("change", sync);
        select.addEventListener("invalid", () => trigger.classList.add("is-invalid"));

        select.parentNode.insertBefore(wrapper, select);
        wrapper.append(select, trigger, menu);
        sync();
    });

    document.addEventListener("click", (event) => {
        if (!event.target.closest(".custom-select")) closeAll();
    });
});
