<select name="category_id" id="category-select" required>
    <option value="">Select Category</option>
    @foreach($categories as $category)
        <option
            value="{{ $category->id }}"
            data-size-type="{{ $category->size_type }}"
            data-default-sizes='@json($category->default_sizes ?? [])'
        >
            {{ $category->name }}
        </option>
    @endforeach
</select>

<div id="size-options-container" style="display:none;">
    <label for="size">Size</label>
    <select name="size" id="size-select">
        <!-- size options will be populated by JS -->
    </select>
</div>

<script>
    const categorySelect = document.getElementById('category-select');
    const sizeContainer = document.getElementById('size-options-container');
    const sizeSelect = document.getElementById('size-select');

    categorySelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const sizeType = selectedOption.getAttribute('data-size-type');
        const sizesJson = selectedOption.getAttribute('data-default-sizes');
        let sizes = [];

        try {
            sizes = JSON.parse(sizesJson);
        } catch(e) {
            sizes = [];
        }

        if (sizeType && sizeType !== 'none' && sizes.length > 0) {
            sizeSelect.innerHTML = ''; // clear old options
            sizes.forEach(size => {
                const opt = document.createElement('option');
                opt.value = size;
                opt.text = size;
                sizeSelect.appendChild(opt);
            });
            sizeContainer.style.display = 'block';
            sizeSelect.required = true;
        } else {
            sizeContainer.style.display = 'none';
            sizeSelect.innerHTML = '';
            sizeSelect.required = false;
        }
    });
</script>
