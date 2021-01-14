import {
    Chart
} from '@antv/g2'

export default {
    methods: {
        renderCategoryChart(data) {
            if (!data.length) {
                this.setMessage('article-chart', '动动小手，写点什么吧~')
                return
            }
            data = this.convertArticleStatisticData(data)

            const chart = new Chart({
                container: "article-chart",
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
                    val = val + '篇';
                    return val;
                },
            });
            
            chart.legend('topic')
            chart
                .interval()
                .position('count')
                .color('topic')
                .label('count', {
                    content: (data) => `${data.topic}:  ${data.count}篇`,
                })
                .adjust('stack')

            chart.render();
        },
        /**
         * 转化文章分类的统计数据
         * 
         * @param {array} data 
         */
        convertArticleStatisticData(data) {
            data = data.map(item => ({
                topic: item.topic ? item.topic.name : '其他',
                count: item.count
            }))

            return data
        }
    }
}
