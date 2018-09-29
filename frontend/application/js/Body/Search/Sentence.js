export const Sentence = {
    template: `
            <div v-if="filters.length > 0" class="Sentence">
                <p>You say: {{sentence}}</p>
            </div>
    `,
    computed: {
        sentence: function() {
            return '';
        }
    },
    props: ['filters']
};