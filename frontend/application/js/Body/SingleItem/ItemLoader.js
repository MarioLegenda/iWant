import { GridLoader } from '@saeris/vue-spinners'

export const ItemLoader = {
    template: `
        <div class="ItemLoaderWrapper">
            <grid-loader :size="30" sizeUnit="px" color="#f44d00"></grid-loader>
        </div>
    `,
    computed: {
        translationsMap: function() {
            return this.$store.state.translationsMap;
        }
    },
    components: {
        'grid-loader': GridLoader,
    }
};