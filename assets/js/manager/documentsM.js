const search = document.getElementById("documentSearch");
const rows = document.querySelectorAll(".document-row");
const categories = document.querySelectorAll(".cat-item");
const tableBody = document.getElementById("documentTableBody");

let selectedCategory = "all";

function filterDocuments() {

    const searchValue = search.value.toLowerCase().trim();

    let visibleCount = 0;

    rows.forEach(row => {

        const category = row.dataset.category.toLowerCase();
        const employee = row.dataset.employee;
        const name = row.dataset.name;

        const categoryMatch =
            selectedCategory === "all" ||
            category === selectedCategory.toLowerCase();

        const searchMatch =
            employee.includes(searchValue) ||
            name.includes(searchValue) ||
            category.includes(searchValue);

        if (categoryMatch && searchMatch) {

            row.style.display = "";
            visibleCount++;

        } else {

            row.style.display = "none";

        }

    });

    let noResults = document.getElementById("noSearchResults");

    if (visibleCount === 0 && rows.length > 0) {

        if (!noResults) {

            noResults = document.createElement("tr");

            noResults.id = "noSearchResults";

            noResults.innerHTML = `
                <td colspan="5" class="no-documents">
                    No documents found.
                </td>
            `;

            tableBody.appendChild(noResults);
        }

        noResults.style.display = "";

    } else if (noResults) {

        noResults.style.display = "none";

    }
}

search.addEventListener("input", filterDocuments);

categories.forEach(category => {

    category.addEventListener("click", function () {

        categories.forEach(item => {
            item.classList.remove("active");
        });

        this.classList.add("active");

        selectedCategory = this.dataset.category;

        filterDocuments();

    });

});