import {
    Chart
} from '@antv/g2'

const IllegalType = {
    1: '举报文章',
    2: '举报评论'
}
export default {
    methods: {
        renderIllegalInfoChart (data) {
            if (!data.length) {
                this.setMessage('illegal-chart', 'Safe and sound~')
                return
            }

            data = this.convertIllegalData(data)

            const chart = new Chart({
                container: "illegal-chart",
                autoFit: true,
                width: "100%",
                height: 230
            });

            chart.coordinate('theta', {
                radius: 0.75,
            });

            // 载入数据源
            chart.data(data);
            
            chart.scale('count', {
                formatter: (val) => {
                    val = val;
                    return val;
                },
            });
            
            chart.legend('topic')
            chart
                .interval()
                .position('count')
                .shape('type')
                .label('count', {
                    content: (data) => `${data.type}:  ${data.count}`,
                })
                .adjust('stack')

            chart.render();
        },
        convertIllegalData (data) {
            return data.map((item) => ({
                count: item.count,
                type: IllegalType[item.type]
            }))
        }
    }
}