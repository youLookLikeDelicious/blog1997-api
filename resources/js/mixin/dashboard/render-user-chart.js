import {
    Chart
} from '@antv/g2'

const userType = {
    1: '微信',
    2: 'github',
    3:'qq'
}
export default {
    methods: {
        renderUserChart(data) {
            if (!data.length) {
                this.setMessage('user-chart', '竭力尽善，成功便不请自来~')
                return
            }
            data = this.convertUserStatistic(data)

            const chart = new Chart({
                container: "user-chart",
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
                .size('type')
                .label('count', {
                    content: (data) => `${data.type}:  ${data.count}`,
                })
                .adjust('stack')

            chart.render();
        },
        /**
         * 转换用户统计数据
         * @param {array}} data 
         */
        convertUserStatistic (data) {
            
            data = data.map(item => ({
                type: userType[item.type],
                count: item.count
            }))

            return data
        }
    }
}