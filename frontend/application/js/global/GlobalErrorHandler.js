import {routes as apiRoutes} from "../apiRoutes";

export const GlobalErrorHandler = {
    data: function() {
        return {
            timer: 10,
            finalReloadText: null,
        }
    },

    template: `
        <div class="GlobalErrorHandler">
            <modal name="request-failed-modal" :height="370">
                <div class="HttpRequestFailed">
                    <div class="ErrorInfo">
                        <h1><i class="fas fa-exclamation-triangle"></i>An unexpected error occurred</h1>
                        
                        <p>I apologise, but something is wrong with the application. The application will refresh in 10 seconds in an effort to solve the problem. If you don't want to wait, just click 'Reload'.</p>
                    </div>
                    
                    <div class="ErrorActions">
                        <div @click="reload" class="ErrorAction">{{(timer !== 0) ? reloadMessage : 'Reloading...'}}</div>
                    </div>
                </div>
            </modal>
        </div>
    `,
    watch: {
        httpRequestFailed(prev, next) {}
    },
    computed: {
        httpRequestFailed: function() {
            const httpRequestFailed = this.$store.state.httpRequestFailed;

            if (typeof httpRequestFailed === 'object' && httpRequestFailed !== null) {
                this.$modal.show('request-failed-modal');

                console.log(httpRequestFailed);

/*                setInterval(() => {
                    this.timer = --this.timer;

                    if (this.timer === 1) {
                        this.reloadText = 'Reloading';
                    }

                    if (this.timer <= 0) {
                        window.location.reload(true);
                    }
                }, 1000);*/

                fetch(apiRoutes.app_post_activity_message, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        activityMessage: {
                            message: httpRequestFailed,
                            additionalData: []
                        },
                    }),
                });
            }

            return httpRequestFailed;
        },

        reloadMessage: function() {
            return `Reload(${this.timer})`
        },
    },
    methods: {
        reload() {
            window.location.reload(true);
        }
    }
};