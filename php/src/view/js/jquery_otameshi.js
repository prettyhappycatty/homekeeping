Ext.onReady(function() {

  // データ定義
  var ssData = [
    ["水星", 4879.4, 0.241, 58.65, 0],
    ["金星", 12103.6, 0.615, 243.0187, 0],
    ["地球", 12756.3, 1.000, 0.997271, 1],
    ["火星", 6794.4, 1.881, 1.02595, 2],
    ["木星", 142984, 11.86, 0.4135, 63],
    ["土星", 120536, 29.46, 0.4264, 63],
    ["天王星", 51118, 84.01, 0.7181, 27],
    ["海王星", 49572, 164.79, 0.6712, 13]
  ];

  // グリッドに表示するデータの準備
  var store = new Ext.data.SimpleStore({
    fields: [
      {name: "name"},                            // 名前
      {name: "diameter", type: "float"},         // 直径
      {name: "orbital-period", type: "float"},   // 公転周期
      {name: "rotation-period", type: "float"},  // 自転周期
      {name: "satellites"}                       // 衛星
    ]
  });
  store.loadData(ssData);  // データの読み込み

  // Ext.grid.GridPanelオブジェクトの生成
  var grid = new Ext.grid.GridPanel({
    store: store,  // 表示するデータ
    columns: [     // 各列の設定
      {header: "名前", width: 80, dataIndex: "name"},
      {header: "直径(km)", width: 120, sortable: true, dataIndex: "diameter"},
      {header: "公転周期(年)", width: 120, sortable: true, dataIndex: "orbital-period"},
      {header: "自転周期(日)", width: 120, sortable: true, dataIndex: "rotation-period"},
      {header: "衛星(個)", width: 80, sortable: true, dataIndex: "satellites"}
    ],
    width: 600,    // グリッドの幅
    height: 300,   // グリッドの高さ
    title: "太陽系"  // グリッドのタイトル
  });

  grid.render("gridcontainer");  // 描画開始
});
