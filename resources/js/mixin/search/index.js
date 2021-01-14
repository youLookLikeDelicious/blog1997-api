export default {
    methods: {
        search() {
            const [query, filter] = [[], this.filter]

            for(const key in filter) {
                if (!filter[key]) {
                    continue
                }
                query.push(`${key}=${filter[key]}`)
            }

            const subFix = query.length ? '?' + query.join('&') : ''
            this.requestApi = this.baseApi + subFix
        }
    }
}
