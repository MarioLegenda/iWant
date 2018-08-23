export const PriceRange = {
    template: `
        <div class="price-range-filter">
            <span>Price from </span>
            <input type="text" />
            <span>to</span>
            <input type="text" />
        </div>
    `,
    props: ['filterData']
};