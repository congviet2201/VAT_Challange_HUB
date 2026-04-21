document.addEventListener("DOMContentLoaded", function () {

    let index = 1;

    window.toggleForm = function () {
        let form = document.getElementById('goalForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    window.addGoal = function () {
        let wrapper = document.getElementById('goals-wrapper');

        let options = '';

        if (window.categories && window.categories.length > 0) {
    window.categories.forEach(cate => {
        options += `<option value="${cate.id}">${cate.name}</option>`;
    });
}

        let html = `
            <div class="goal-item border p-3 mb-3 rounded position-relative">

                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2"
                        onclick="this.parentElement.remove()">
                    <i class="bi bi-x"></i>
                </button>

                <input class="form-control mb-2" type="text" name="goals[${index}][title]" placeholder="Tên mục tiêu" required>

                <select name="goals[${index}][category_id]" class="form-select mb-2">
                    ${options}
                </select>

                <textarea class="form-control" name="goals[${index}][description]" placeholder="Mô tả"></textarea>
            </div>
        `;

        wrapper.insertAdjacentHTML('beforeend', html);
        index++;
    }

});
