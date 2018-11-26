export const HideDuplicateItems = {
    template: `
        <div class="SingleFilterWrapper">
            <toggle-button 
                id="changed-font"
                :sync="true" 
                @change="onChange" 
                :labels="{checked: 'Hide duplicate items', unchecked: 'Show duplicate items'}"
                :width="185"
                :height="40"></toggle-button>
        </div>
    `,
    methods: {
        onChange: function(event) {
        }
    }
};