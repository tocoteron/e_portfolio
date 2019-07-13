console.log("statistics_chart.js");

console.log("param_statistics_chart:");
console.log(param_statistics_chart);

var selected_evaluation = [];

var assignment_titles = [];
var evaluation_grades = [];
var comments = [];

// 自己評価
evaluation_grades["self"] = [];
comments["self"] = [];
for(assignment_id in param_statistics_chart["self_evaluation"])
{
    selected_evaluation[0] = {
        evaluator: "self",
        assignment_id: assignment_id
    };

    selected_evaluation[1] = {
        evaluator: "self",
        assignment_id: assignment_id
    };

    assignment_titles[assignment_id] = param_statistics_chart["title"][assignment_id];

    evaluation_grades["self"][assignment_id] = [];
    for(category_id in param_statistics_chart["self_evaluation"][assignment_id])
    {
        evaluation_grades["self"][assignment_id][category_id] = [];
        for(item_id in param_statistics_chart["self_evaluation"][assignment_id][category_id])
        {
            evaluation_grades["self"][assignment_id][category_id][item_id - 1] = param_statistics_chart["self_evaluation"][assignment_id][category_id][item_id];
        }
    }

    comments["self"][assignment_id] = param_statistics_chart["self_evaluation"][assignment_id]["comments"];
}

// 教員評価
evaluation_grades["teacher"] = [];
comments["teacher"] = [];
for(assignment_id in param_statistics_chart["teacher_evaluation"])
{
    selected_evaluation[0] = {
        evaluator: "teacher",
        assignment_id: assignment_id
    };

    selected_evaluation[1] = {
        evaluator: "teacher",
        assignment_id: assignment_id
    };

    assignment_titles[assignment_id] = param_statistics_chart["title"][assignment_id];

    evaluation_grades["teacher"][assignment_id] = [];
    for(category_id in param_statistics_chart["teacher_evaluation"][assignment_id])
    {
        evaluation_grades["teacher"][assignment_id][category_id] = [];
        for(item_id in param_statistics_chart["teacher_evaluation"][assignment_id][category_id])
        {
            evaluation_grades["teacher"][assignment_id][category_id][item_id - 1] = param_statistics_chart["teacher_evaluation"][assignment_id][category_id][item_id];
        }
    }

    comments["teacher"][assignment_id] = param_statistics_chart["teacher_evaluation"][assignment_id]["comments"];
}

//console.log(comments);

console.log("evaluation_grades:");
console.log(evaluation_grades);

var chartType = "radar";

// グラフの線の色
var colors = [
    'RGBA(237, 109, 31, 1)',
    'RGBA(9, 155, 133, 1)'
];

// 各チャートのタイトル
var titles = [
    "Organization",
    "Vocabulary",
    "Grammar"
];

// 各チャート内の項目(軸)
var labels = [
    ["Introduction", "Claim", "Reasons", "Evidence", "Conclusion"],
    ["Range", "Accuracy", "Spelling"],
    ["Tense", "Aspect", "3PS", "Voice", "Count", "Articles", "Pronouns", "RPs", "Adv/Adj", "Conj", "P"],
]

var charts = [];

// レーダーチャートを生成
for (let i = 0; i < 3; ++i) {

    var options = {
        responsive: false,
        title: {
            display: true,
            text: titles[i],
            fontSize: 24
        },
        scale: {
            ticks: {
                suggestedMin: 0,
                suggestedMax: 5,
                stepSize: 1,
                fontSize: 14,
            },
            pointLabels: {
                fontSize: 14
            },
        },
        legend: {
            labels: {
                fontSize: 16,
            }
        },
    }

    charts.push(
        new Chart(document.getElementById("chart1-" + (i + 1)), {
            type: chartType,
            data: {},
            options: options
        })
    );

    updateChartDatasets(charts[i], labels[i], [
        createData("Assignment 1", [], colors[0]),
        createData("Assignment 2", [], colors[1])
    ]);
}

// レーダーチャートのデータを変更
function selectEvaluation(data_id, evaluator, assignment_id)
{
    // 選択している評価を更新
    selected_evaluation[data_id] = {
        evaluator: evaluator,
        assignment_id: assignment_id
    };

    // 各レーダーチャートのデータを更新
    for(let i = 0; i < 3; ++i)
    {
        updateChartDatasets(charts[i], labels[i], [
            createData(
                assignment_titles[selected_evaluation[0]["assignment_id"]],
                evaluation_grades[selected_evaluation[0]["evaluator"]][selected_evaluation[0]["assignment_id"]][i + 1],
                colors[0]
            ),
            createData(
                assignment_titles[selected_evaluation[1]["assignment_id"]],
                evaluation_grades[selected_evaluation[1]["evaluator"]][selected_evaluation[1]["assignment_id"]][i + 1],
                colors[1]
            )
        ]);
    }
    
    // 選択している2つの課題のスコアを取得
    let over_all_scores = [
        evaluation_grades[selected_evaluation[0]["evaluator"]][selected_evaluation[0]["assignment_id"]][4][0],
        evaluation_grades[selected_evaluation[1]["evaluator"]][selected_evaluation[1]["assignment_id"]][4][0]
    ];

    // 平均スコアを表示
    $("#over-all-score").html("Overall score: " + ((over_all_scores[0] + over_all_scores[1]) / 2) + " / 5 points");

    // コメント欄を更新
    $("#comments1").html(comments[selected_evaluation[0]["evaluator"]][selected_evaluation[0]["assignment_id"]]);
    $("#comments2").html(comments[selected_evaluation[1]["evaluator"]][selected_evaluation[1]["assignment_id"]]);
}

function updateChartDatasets(chart, labels, datasets) {
    chart.data.labels = labels;
    chart.data.datasets = datasets;
    chart.update();
}

function createData(label, data, color)
{
    return {
        label: label,
        data: data,
        borderColor: color,
        borderWidth: 2,
        pointBackgroundColor: color,
        fill: false
    };
}