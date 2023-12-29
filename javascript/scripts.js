function scrollHContainer(scrollAmount,direction,container) {
    if(direction === 'left')
        container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    else
    container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
}


const filterBtn = document.getElementById('filter');
const filterBox = document.getElementById('filterForm');
const sortBox = document.getElementById('sortForm');
let isFilterVisible = false;

if(filterBox != null){
filterBtn.addEventListener('click', async () => {
    if (isFilterVisible) {
        filterBox.style.display = 'none';
        sortBox.style.display = 'none';
    } else {
        filterBox.style.display = 'block';
        sortBox.style.display = 'block';
    }
    isFilterVisible = !isFilterVisible;
});}


