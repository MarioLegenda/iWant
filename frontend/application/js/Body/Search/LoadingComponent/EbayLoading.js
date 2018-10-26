export const EbayLoading = {
    data: function() {
        return {
            loadingIcons: [],
        }
    },
    created() {
        for (const globalId in this.$globalIdInformation.all) {
            if (this.$globalIdInformation.all.hasOwnProperty(globalId)) {
                this.loadingIcons.push(`/images/country_icons/${globalId}.svg`);
            }
        }
    },
    template: `
            <div class="EbayLoading">
                <div 
                    v-for="(item, index) in loadingIcons" 
                    :key="index"
                    class="ImageWrapper">
                    
                    <div class="ImageHider"></div>
                    <img class="Image" :src="item" />
                </div>
            </div>
    `,
};