export const NameValueContainer = {
    template: `<div class="NameValueRow NameValueContainer">
                   <div v-if="isNameDefined() === false">
                       <p class="FullWidthName">{{computedValue}}</p>
                   </div>
                   
                   <div v-else-if="isNameDefined() === true">
                       <p class="Name">{{computedName}}</p>
                       <p class="Value">{{computedValue}}</p>
                   </div>
               </div>`,
    created() {
        if (isEmpty(this.name) && isEmpty(this.value)) {
            throw new Error('\'name\' and \'value\' passed properties have to have a string value');
        }
    },
    props: ['name', 'value'],
    computed: {
        computedName: function() {
            return this.name;
        },

        computedValue: function() {
            return this.value;
        }
    },
    methods: {
        isNameDefined() {
            if (isEmpty(this.name)) {
                return false;
            }

            return true;
        }
    }
};