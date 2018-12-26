export const GlobalErrorHandler = {
    template: `
        <div class="GlobalErrorHandler">
            <modal name="http-request-failed-modal" v-if="httpRequestFailed">
                
            </modal>
        </div>
    `,
    watch: {
        httpRequestFailed(prev, next) {}
    },
    computed: {
        httpRequestFailed: function() {
            // for now, just return this
            const httpRequestFailed = this.$store.state.httpRequestFailed;

            return httpRequestFailed;
        }
    }
};