export const toggleButtonMixin = {
    data: function() {
        return {
            toggleButtonWidth: 170,
            toggleButtonHeight: 25,
        }
    },
    created() {
        const vGeo = getViewportDimensions();

        if (vGeo.width < 480) {
            this.toggleButtonWidth = 270;
        }
    }
};