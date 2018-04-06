<?php
// alert_media.php
// ニコニコアラートのチェックする動画・静画

$medilist = array(
			// 接尾辞(sm/nm/im/mg)除く
			"3000000", // im
			"20000000", // sm
			);
$userlist = array(
			// channel
			"ch253" => "初音ミク ―Project DIVA―",
			"ch765" => "ニコニコアイマスｃｈ“たるき亭”",
			"ch203" => "Lantisちゃんねる",
			"ch312" => "アニメロチャンネル",
			"ch3330" => "ダ･ヴィンチちゃんねる",
			"ch2589719" => "日テレちゃんねる",
			"ch2589908" => "シーサイドチャンネル",
			"ch2633237" => "『デレラジ』",
			"ch2599508" => "CINDERELLA PARTY!",
			"ch2615052" => "高森奈津美のP！ットイン★ラジオ",
			"ch2627807" => "三上枝織のみかっしょ！",
			"ch2627924" => "大久保瑠美・原紗友里 青春学園 Girls High↑↑",
			"ch2627954" => "津田のラジオ「っだー！！」",
			"ch2612057" => "Voice-Styleチャンネル",

			// anime
			"ch60045" => "アイドルマスター",
			"ch639" => "俺の妹がこんなに可愛いわけがない",
			"ch260" => "魔法少女まどか☆マギカ",
			"ch60005" => "日常",
			"ch60015" => "Aチャンネル",
			"ch60001" => "シュタインズ・ゲート",
			"ch60036" => "ゆるゆり",
			"ch60090" => "Fate/Zero",
			"ch60093" => "未来日記",
			"ch60089" => "ベン・トー",
			"ch60088" => "ペルソナ4",
			"ch5050" => "gdgd妖精s",
			"ch60198" => "戦姫絶唱シンフォギア",
			"ch60190" => "モーレツ宇宙海賊",
			"ch60195" => "妖狐×僕SS",
			"ch60238" => "アクセル・ワールド",
			"ch60243" => "しばいぬ子さん",
			"ch60251" => "秘密結社 鷹の爪 NEO",
			"ch60250" => "咲-Saki-阿知賀編 episode of side-A",
			// 2012/summer
			"ch60349" => "アルカナ・ファミリア",
			"ch60336" => "織田信奈の野望",
			"ch60337" => "カンピオーネ！",
			"ch60344" => "ココロコネクト",
			"ch60345" => "じょしらく",
			"ch60347" => "ソードアート・オンライン",
			"ch60348" => "だから僕は、Hができない。",
			"ch60339" => "TARI TARI",
			"ch60341" => "ちとせげっちゅ!!",
			"ch60342" => "トータル・イクリプス",
			"ch60351" => "薄桜鬼 黎明録",
			"ch60338" => "ゆるゆり♪♪", 
			"ch60346" => "ゆるめいつ３でぃPLUS",
			"ch60350" => "人類は衰退しました",
			"ch60352" => "うた恋い。",
			// 2012/autumn
			"ch60388" => "うーさーのその日暮らし",
			"ch60357" => "生徒会の一存 新アニメ",
			"ch60385" => "BTOOOM!",
			"ch60386" => "イクシオンサーガDT",
			"ch60391" => "お兄ちゃんだけど愛さえあれば関係ないよねっ",
			"ch60389" => "K",
			"ch60393" => "さくら荘のペットな彼女",
			"ch60383" => "ジョジョの奇妙な冒険",
			"ch60394" => "新世界より",
			"ch60390" => "好きっていいなよ。",
			"ch60396" => "となりの怪物くん",
			"ch60387" => "リトルバスターズ！",
			"ch60380" => "めだかボックス　アブノーマル",
			"ch60382" => "ヨルムンガンド　PERFECT ORDER",
			"ch60229" => "緋色の欠片",
			"ch60397" => "ハヤテのごとく！ CAN'T TAKE MY EYES OFF YOU",
			"ch1212" => "てーきゅう",
			// 2013/winter
			"ch60410" => "あいまいみー",
			"ch60411" => "探偵オペラ ミルキィホームズ Alternative TWO -小林オペラと虚空の大鴉-",
			//"ch60415" => "戦勇。",
			"ch60413" => "猫物語(黒)",
			"ch60418" => "D.C.3 -ダ・カーポ3-",
			"ch60421" => "俺の彼女と幼なじみが修羅場すぎる",
			"ch60422" => "琴浦さん",
			"ch60423" => "ぷちます！-PETIT IDOLM@STER-",
			"ch60424" => "キューティクル探偵因幡",
			//"ch60425" => "電撃アニメ祭",
			"ch60426" => "まおゆう魔王勇者",
			"ch1457" => "ヤマノススメ",
			"ch1428" => "gdgd妖精s",
			"ch1426" => "僕の妹は「大阪おかん」",
			"ch1494" => "まんがーる！",
			"ch2526031" => "THE UNLIMITED 兵部京介",
			"ch2526032" => "AMNESIA",
			"ch2526034" => "ラブライブ！",
			"ch2526096" => "閃乱カグラ",
			"ch2526097" => "ビビッドレッド・オペレーション",
			"ch2526098" => "ヘタリア The Beautiful World",
			"ch2526118" => "直球表題ロボットアニメ",
			"ch2567623" => "ささみさん＠がんばらない",
			//2013/spring
			"ch2563775" => "あいうら",
			"ch2568046" => "惡の華",
			"ch2568763" => "うたの☆プリンスさまっ♪ マジLOVE2000％",
			"ch2567086" => "俺の妹がこんなに可愛いわけがない。",
			//"ch2567081" => "革命機ヴァルヴレイヴ",
			"ch2565318" => "銀河機攻隊 マジェスティックプリンス",
			"ch2566378" => "血液型くん！",
			"ch2565327" => "進撃の巨人",
			"ch2567313" => "翠星のガルガンティア",
			"ch2566372" => "スパロウズホテル",
			"ch2569582" => "絶対防衛レヴィアタン",
			"ch2569297" => "断裁分離のクライムエッジ",
			"ch2528113" => "デート・ア・ライブ",
			"ch2567330" => "DEVIL SURVIVOR2 the ANIMATION",
			"ch2567097" => "とある科学の超電磁砲S",
			"ch2568047" => "波打際のむろみさん",
			"ch2562762" => "這いよれ！ニャル子さんＷ",
			"ch2567093" => "はたらく魔王さま！",
			"ch2568219" => "ハヤテのごとく！ Cuties",
			"ch2569290" => "鷹の爪MAX",
			"ch2567319" => "百花繚乱 サムライブライド",
			"ch2569306" => "フォトカノ",
			"ch2568065" => "プリティーリズム・レインボーライブ",
			"ch2569579" => "やはり俺の青春ラブコメはまちがっている。",
			"ch2568218" => "ゆゆ式",
			"ch2568048" => "よんでますよ、アザゼルさん。Z",
			"ch2527996" => "RDG レッドデータガール",
			//2013/summer
			"ch2575233" => "犬とハサミは使いよう",
			"ch2576245" => "神さまのいない日曜日",
			"ch2576246" => "神のみぞ知るセカイ　女神篇",
			"ch2576110" => "君のいる町",
			"ch2576574" => "きんいろモザイク",
			"ch2575731" => "劇場版｢空の境界｣",
			"ch2575662" => "幻影ヲ駆ケル太陽",
			"ch2576111" => "げんしけん二代目",
			"ch2575665" => "サーバント×サービス",
			"ch2574251" => "ステラ女学院高等科C3部",
			"ch2575849" => "戦姫絶唱シンフォギアＧ",
			"ch60415" => "戦勇。",
			"ch2575837" => "たまゆら -もあぐれっしぶ-",
			"ch2576374" => "ダンガンロンパ　Ｔｈｅ　Ａｎｉｍａｔｉｏｎ",
			"ch2575898" => "超次元ゲイム ネプテューヌ",
			"ch2576296" => "てーきゅう2期",
			"ch2576576" => "ハイスクールD×D NEW",
			"ch2576643" => "ファンタジスタドール",
			"ch2576363" => "ふたりはミルキィホームズ",
			"ch2576572" => "BROTHERS CONFLICT(ブラザーズ コンフリクト)",
			"ch2576247" => "Free！",
			"ch2576314" => "Fate/kaleid liner プリズマ☆イリヤ",
			"ch2575664" => "<物語>シリーズセカンドシーズン",
			"ch2576248" => "魔界王子 devils and realist",
			"ch2574861" => "リコーダーとランドセル　ミ☆",
			"ch2575651" => "恋愛ラボ",
			"ch2576244" => "ロウきゅーぶ！SS",
			"ch2574860" => "ローゼンメイデン",
			"ch2576575" => "私がモテないのはどう考えてもお前らが悪い！",
			//2013/autumn
			"ch2582279" => "アウトブレイク・カンパニー",
			"ch2580055" => "蒼き鋼のアルペジオ -アルス・ノヴァ-",
			"ch2582002" => "IS＜インフィニット・ストラトス＞2",
			"ch2567081" => "革命機ヴァルヴレイヴ",
			"ch2581768" => "境界の彼方",
			"ch2581936" => "京騒戯画",
			"ch2581999" => "ぎんぎつね",
			"ch2580588" => "キルラキル KILL la KILL",
			"ch2581383" => "ゴールデンタイム",
			"ch2581385" => "ストライク・ザ・ブラッド",
			"ch2582360" => "声優戦隊ボイストーム7",
			"ch2579855" => "せかつよチャンネル",
			"ch2582357" => "ダイヤのA",
			"ch2581926" => "てーきゅう３期チャンネル",
			"ch2580972" => "DIABOLIK LOVERS",
			//"ch2576710" => "てさぐれ！部活もの",
			"ch2581937" => "東京レイヴンズ",
			"ch2581384" => "凪のあすから",
			"ch2581583" => "のんのんびより",
			//"ch2581671" => "pupaチャンネル",
			"ch2581660" => "BLAZBLUE ALTER MEMORY",
			"ch2581585" => "フリージング ヴァイブレーション",
			"ch2580590" => "WHITE ALBUM2",
			"ch2581586" => "機巧少女は傷つかない",
			"ch2581984" => "ミス・モノクローム",
			"ch2580973" => "メガネブ！",
			"ch2580921" => "夜桜四重奏-ハナノウタ-",
			"ch2581285" => "弱虫ペダル",
			"ch2581661" => "リトルバスターズ！-Refrain-",
			"ch2582005" => "ワルキューレロマンツェ",
			//2014/winter
			"ch2581668" => "うーさーのその日暮らし 覚醒編",
			"ch2585704" => "ウィッチクラフトワークス",
			"ch2585574" => "Wake Up, Girls！",
			"ch2584917" => "お姉ちゃんが来た",
			"ch2585707" => "GO！GO！575",
			"ch2585336" => "最近、妹のようすがちょっとおかしいんだが。",
			"ch2585610" => "咲-Saki-全国編",
			"ch2585935" => "桜Trick",
			"ch2585576" => "世界征服～謀略のズヴィズダー～ ",
			"ch2585289" => "そにアニ　-SUPER SONICO THE ANIMATION-",
			"ch2585064" => "ストレンジ・プラス",
			"ch2585963" => "生徒会役員共＊",
			"ch2585815" => "Z/X IGNITION",
			//"chxxxxx" => "中二病でも恋がしたい!戀",
			"ch2585304" => "ディーふらぐ！",
			"ch2585251" => "とある飛空士への恋歌",
			"ch2583388" => "となりの関くん",
			"ch2585575" => "ニセコイ",
			"ch2577243" => "のうりん",
			"ch2585524" => "ノラガミ",
			"ch2585306" => "ノブナガ・ザ・フール",
			"ch2585063" => "ノブナガン",
			"ch2585705" => "ハマトラ",
			"ch2581671" => "pupaチャンネル",
			"ch2585820" => "鬼灯の冷徹",
			"ch2585303" => "魔法戦争",
			"ch2585573" => "未確認で進行形",
			"ch2582363" => "ロボットガールズZ",
			//2014/spring
			"ch2590140" => "悪魔のリドル",
			"ch2590942" => "一週間フレンズ。",
			"ch2589898" => "犬神さんと猫山さん",
			"ch2590218" => "エスカ&ロジーのアトリエ〜黄昏の空の錬金術士〜",
			"ch2590502" => "M３〜ソノ黒キ鋼〜",
			"ch2590550" => "オレカバトル",
			"ch2590084" => "カードファイト!! ヴァンガード　レギオンメイト編",
			"ch2590142" => "彼女がフラグをおられたら",
			"ch2590262" => "神々の悪戯",
			"ch2590980" => "超爆裂異次元メンコバトル ギガントシューター つかさ",
			"ch2590584" => "金色のコルダ Blue♪Sky",
			"ch2590222" => "健全ロボ ダイミダラー",
			"ch2590541" => "極黒のブリュンヒルデ",
			"ch2591203" => "ご注文はうさぎですか？",
			"ch2590854" => "シドニアの騎士",
			"ch2590164" => "ジョジョの奇妙な冒険 スターダストクルセイダース",
			"ch2590227" => "星刻の竜騎士",
			"ch2590259" => "selector infected WIXOSS",
			"ch2590225" => "ソウルイーターノット！",
			"ch2590856" => "それでも世界は美しい",
			"ch2591033" => "一週間フレンズ。",
			"ch2590487" => "テンカイナイト",
			"ch2590551" => "ドラゴンコレクション",
			"ch2590219" => "ノーゲーム・ノーライフ",
			"ch2590815" => "秘密結社 鷹の爪 エクストリーム",
			"ch2590488" => "FAIRY TAIL",
			"ch2590261" => "ぷちます！！‐プチプチ・アイドルマスター‐",
			"ch2590602" => "ブラック・ブレット",
			"ch2590264" => "ブレイドアンドソウル",
			"ch2590816" => "僕らはみんな河合荘",
			"ch2591053" => "マジンボーン",
			"ch2590586" => "魔法科高校の劣等生",
			"ch2590603" => "マンガ家さんとアシスタントさんと",
			"ch2590206" => "蟲師　続章",
			"ch2587663" => "メカクシティアクターズ",
			"ch2590260" => "召しませロードス島戦記～それっておいしいの？～",
			"ch2590587" => "ラブライブ！2期",
			//2014/summer
			"ch2591055" => "あいまいみー～妄想カタストロフ～",
			"ch2595651" => "アオハライド",
			"ch2595567" => "アカメが斬る！",
			//"ch2594935" => "アルドノア・ゼロ",
			"ch2595351" => "グラスリップ",
			"ch2595683" => "黒執事Book of Circus",
			"ch2594945" => "月刊少女 野崎くん",
			"ch2595346" => "さばげぶっ！",
			"ch2595414" => "少年ハリウッド",
			"ch2595391" => "白銀の意思　アルジェヴォルン",
			"ch2594859" => "人生相談テレビアニメーション「人生」",
			"ch2594980" => "真 ストレンジ・プラス",
			"ch2587524" => "スペース☆ダンディ",
			"ch2594944" => "精霊使いの剣舞",
			"ch2595988" => "戦国BASARA Judge End",
			"ch2595362" => "ソードアート・オンラインⅡ",
			"ch2595307" => "東京喰種",
			"ch2595176" => "ドラマティカルマーダー",
			"ch2595036" => "幕末Rock",
			"ch2595174" => "ハナヤマタ",
			"ch2595987" => "ばらかもん",
			"ch2594336" => "美少女戦士セーラームーンCrystal",
			"ch2593401" => "ひめゴト",
			"ch2595298" => "普通の女子高生が【ろこどる】やってみた。",
			"ch2596264" => "フランチェスカ",
			"ch2594938" => "TVアニメ『Free!-Eternal Summer-』",
			"ch2595271" => "プリパラ",
			"ch2594936" => "TVアニメ「ペルソナ4 ザ・ゴールデン」",
			"ch2595858" => "毎度！浦安鉄筋家族",
			"ch2594939" => "まじもじるるも",
			"ch2591608" => "みならいディーバ",
			"ch2594979" => "モモキュンソード",
			"ch2594241" => "ヤマノススメ セカンドシーズン",
			"ch2595297" => "RAIL WARS!",
			"ch2595224" => "リプライ ハマトラ",
			"ch2595352" => "六畳間の侵略者!?",
			"ch2598440" => "おへんろ。ー八十八歩記ー",
			//2014/autumn
			"ch2599570" => "愛・天地無用！",
			"ch2598956" => "異能バトルは日常系のなかで",
			"ch2599558" => "オオカミ少女と黒王子",
			"ch2599996" => "ガールフレンド（仮）",
			"ch2599672" => "繰繰れ！コックリさん",
			"ch2599474" => "グリザイアの果実",
			"ch2599576" => "山賊の娘ローニャ",
			"ch2598859" => "神撃のバハムートGENESIS",
			"ch2599573" => "SHIROBAKO",
			"ch2599784" => "selector spread WIXOSS",
			"ch2599253" => "大図書館の羊飼い",
			"ch2590230" => "TERRAFORMARS",
			"ch2599088" => "トリニティセブン",
			"ch2599997" => "七つの大罪",
			"ch2598813" => "デンキ街の本屋さん",
			"ch2599443" => "なりヒロｗｗｗ",
			"ch2597633" => "Hi☆sCoool！ セハガール",
			//"ch2598888" => "Fate/stay night",
			"ch2596677" => "Bonjour♪恋味パティスリー",
			"ch2599673" => "魔弾の王と戦姫",
			"ch2599128" => "弱虫ペダル GRANDE ROAD",
			"ch2599210" => "暁のヨナ",
			"ch2600168" => "甘城ブリリアントパーク",
			"ch2599572" => "失われた未来を求めて",
			"ch2600405" => "俺、ツインテールになります。",
			"ch2597475" => "オレん家のフロ事情",
			"ch2598938" => "怪盗ジョーカー",
			"ch2599694" => "寄生獣 セイの格率",
			"ch2599873" => "実在性ミリオンアーサー",
			"ch2598937" => "天体のメソッド",
			"ch2599169" => "旦那が何を言っているかわからない件",
			"ch2599995" => "曇天に笑う",
			//"ch2590206" => "蟲師 続章",
			"ch2599669" => "結城友奈は勇者である",
			//2015/winter
			"ch2602856" => "アニメで分かる心療内科",
			"ch2602896" => "アブソリュート・デュオ",
			"ch2594935" => "アルドノア・ゼロ(2クール)",
			"ch2602980" => "幸腹グラフィティ",
			"ch2597821" => "キュートランスフォーマー 帰ってきたコンボイの謎  ",
			"ch2602662" => "銃皇無尽のファフニール",
			"ch2603188" => "ジョジョの奇妙な冒険 スターダストクルセイダース エジプト編",
			"ch2603114" => "聖剣使いの禁呪詠唱（ワールドブレイク）",
			"ch2603111" => "探偵歌劇 ミルキィホームズ TD",
			"ch2602905" => "デュラララ!!×２ 承",
			"ch2602578" => "ぱんきす！2次元",
			"ch2602658" => "美男高校地球防衛部LOVE！",
			"ch2603013" => "みりたり！",
			"ch2602700" => "みんな集まれ！ファルコム学園SC",
			"ch2603523" => "艦隊これくしょん -艦これ-",
			"ch2602382" => "アイドルマスターシンデレラガールズ",
			"ch2602586" => "神様はじめました◎",
			"ch2603143" => "戦国無双",
			"ch2603377" => "少年ハリウッド-HOLLY STAGE FOR 50-",
			"ch2594327" => "蒼穹のファフナー EXODUS",
			"ch2603109" => "デス・パレード",
			"ch2602540" => "東京喰種トーキョーグール√A",
			"ch2603277" => "ユリ熊嵐",
			"ch2602974" => "夜ノヤッターマン",
			"ch2602314" => "ローリング☆ガールズ",
			//2015/spring
			"ch2607723" => "雨色ココア",
			"ch2607478" => "アルティメット・スパイダーマン ウェブ・ウォーリアーズ",
			"ch2607177" => "VAMPIRE HOLMES",
			"ch2606196" => "浦和の調ちゃん",
			"ch2607373" => "うたの☆プリンスさまっ♪ マジLOVEレボリューションズ",
			"ch2607080" => "おまかせ！みらくるキャット団",
			"ch2607394" => "俺物語!!",
			"ch2607420" => "終わりのセラフ",
			"ch2607265" => "ガンスリンガー ストラトス -THE ANIMATION-",
			"ch2605725" => "境界のRINNE",
			"ch2607480" => "銀魂゜",
			"ch2607418" => "グリザイアの迷宮",
			"ch2607419" => "グリザイアの楽園",
			"ch2607110" => "SHOW BY ROCK!!",
			"ch2606849" => "食戟のソーマ",
			"ch2606743" => "ダンジョンに出会いを求めるのは間違っているだろうか",
			"ch2606762" => "旦那が何を言っているかわからない件 2スレ目",
			"ch2606623" => "高宮なすのです！",
			"ch2606543" => "てーきゅう4期",
			"ch2606006" => "デュエル・マスターズ VSR",
			"ch2607266" => "ニセコイ：",
			"ch2576719" => "にゅるにゅる!!KAKUSENくん 2期",
			"ch2606797" => "ニンジャスレイヤー フロムアニメイシヨン",
			"ch2607260" => "ハロー!!きんいろモザイク",
			"ch2606941" => "響け！ユーフォニアム",
			"ch2598888" => "Fate/stay night -Unlimited Blade Works- 2ndシーズン",
			"ch2608034" => "フューチャーカード バディファイト ハンドレッド",
			"ch2607264" => "プラスティック・メモリーズ",
			"ch2606448" => "ベイビーステップ 第2シリーズ",
			"ch2607261" => "ミカグラ学園組曲",
			"ch2607162" => "秘密結社 鷹の爪 DO（ドゥー）",
			"ch2607545" => "アルスラーン戦記",
			"ch2602278" => "えとたま -干支魂-",
			"ch2606159" => "血界戦線",
			"ch2606890" => "シドニアの騎士 第九惑星戦役",
			"ch2607777" => "ダイヤのA -SECOND SEASON-",
			"ch2576710" => "てさぐれ！部活もの すぴんおふ プルプルんシャルムと遊ぼう",
			//"ch2605854" => "トランスフォーマー アドベンチャー",
			"ch2607481" => "トリアージX",
			"ch2607395" => "長門有希ちゃんの消失",
			"ch2607257" => "ハイスクールD×D BorN",
			"ch2607482" => "ふなっしーのふなふなふな日和",
			"ch2606891" => "プリパラ　2nd season",
			"ch2606855" => "放課後のプレアデス",
			"ch2607998" => "やはり俺の青春ラブコメはまちがっている。続",
			"ch2607263" => "山田くんと7人の魔女",
			"ch2607166" => "レーカン！",
			//2015/summer
			"ch2610846" => "青春×機関銃",
			"ch2610897" => "VENUS PROJECT",
			"ch2610838" => "うーさーのその日暮らし 夢幻編",
			"ch2610527" => "おくさまが生徒会長！",
			"ch2610989" => "がっこうぐらし!",
			"ch2610688" => "GATE（ゲート）自衛隊 彼の地にて、斯く戦えり",
			"ch2610950" => "ケイオスドラゴン～赤竜戦役～",
			"ch2610619" => "TVアニメ「Charlotte(シャーロット)」",
			"ch2610570" => "洲崎西THE ANIMATION",
			"ch2610809" => "戦姫絶唱シンフォギアGX",
			"ch2610953" => "デュラララ!!×２ 転",
			"ch2610526" => "だんちがい",
			"ch2611134" => "To LOVEる－とらぶる－ダークネス2nd",
			"ch2610948" => "干物妹！うまるちゃん",
			"ch2610003" => "監獄学園〈プリズンスクール〉",
			"ch2610952" => "モンスター娘のいる日常",
			"ch2610901" => "ビキニ・ウォリアーズ",
			"ch2610378" => "六花の勇者",
			"ch2611198" => "WORKING!!!",
			"ch2610908" => "赤髪の白雪姫",
			"ch2610889" => "アクエリオンロゴス",
			//"ch2611643" => "ウルトラスーパーアニメタイム",
			"ch2610890" => "オーバーロード",
			"ch2610562" => "ガッチャマン クラウズ インサイト",
			"ch2610981" => "GANGSTA.",
			"ch2610960" => "空戦魔導士候補生の教官",
			"ch2611138" => "Classroom☆Crisis",
			"ch2610947" => "GOD EATER（ゴッドイーター）",
			"ch2610806" => "実は私は",
			"ch2610942" => "下ネタという概念が存在しない退屈な世界",
			"ch2610939" => "城下町のダンデライオン",
			"ch2610909" => "それが声優！",
			"ch2610365" => "てーきゅう 5期",
			"ch2610840" => "のんのんびより りぴーと",
			"ch2610959" => "Fate/kaleid liner プリズマ☆イリヤ ツヴァイ ヘルツ!",
			"ch2610938" => "ヘタリア The World Twinkle",
			"ch2609784" => "枕男子",
			"ch2610943" => "ミス・モノクローム-The Animation- 2",
			"ch2608685" => "ミリオンドール",
			"ch2611160" => "ワカコ酒",
			"ch2611424" => "わかば＊ガール",
			//2015/autumn
			"ch2612507" => "ワンパンマン",
			"ch2607776" => "Dance with Devils",
			"ch2613787" => "DIABOLIK LOVERS MORE,BLOOD",
			"ch2614617" => "雨色ココア Rainy colorへようこそ！",
			"ch2614386" => "あにトレ！EX",
			"ch2614797" => "あにトレ！EX",
			"ch2614387" => "ヴァルキリードライヴ マーメイド",
			"ch2614280" => "うたわれるもの 偽りの仮面",
			"ch2614839" => "うたわれるもの 偽りの仮面",
			"ch2614008" => "俺がお嬢様学校に「庶民サンプル」としてゲッツされた件",
			"ch2607420" => "終わりのセラフ 第２クール",
			"ch2614318" => "終物語",
			"ch2614001" => "学戦都市アスタリスク",
			"ch2614376" => "影鰐-KAGEWANI-",
			"ch2614373" => "かみさまみならい ヒミツのここたま",
			"ch2614378" => "血液型くん！3期",
			"ch2614799" => "血液型くん！3期",
			"ch2614801" => "小森さんは断れない！",
			"ch2614149" => "進撃！巨人中学校",
			"ch2614086" => "スタミュ",
			"ch2614385" => "てーきゅう　6期",
			"ch2614800" => "てーきゅう　6期",
			"ch2614379" => "ハッカドール THE あにめ〜しょん",
			"ch2614002" => "不思議なソメラちゃん",
			"ch2614747" => "ヘヴィーオブジェクト",
			"ch2614648" => "DD北斗の拳2　イチゴ味＋",
			"ch2614177" => "ミス・モノクローム-The Animation- 3",
			"ch2614638" => "ゆるゆり さん☆ハイ！",
			"ch2611643" => "ウルトラスーパーアニメタイム（第2クール）",
			"ch2614611" => "おそ松さん",
			"ch2613786" => "温泉幼精ハコネちゃん",
			"ch2614382" => "牙狼 -紅蓮ノ月-",
			"ch2614418" => "金田一少年の事件簿R（2015）",
			"ch2614178" => "K RETURN OF KINGS",
			"ch2614174" => "ご注文はうさぎですか？？",
			"ch2614841" => "櫻子さんの足下には死体が埋まっている",
			"ch2614882" => "新妹魔王の契約者 BURST",
			"ch2614389" => "スター・ウォーズ 反乱者たち",
			"ch2594327" => "蒼穹のファフナー EXODUS（第2クール）",
			"ch2614881" => "対魔導学園35試験小隊",
			"ch2614390" => "ちいさなプリンセス ソフィア",
			"ch2605854" => "トランスフォーマーアドベンチャー",
			"ch2614347" => "ノラガミ ARAGOTO",
			"ch2614604" => "ハイキュー!! セカンドシーズン",
			"ch2614639" => "Peeping Life TV シーズン1 ??",
			"ch2614753" => "緋弾のアリアAA",
			"ch2614011" => "ヤング ブラック・ジャック",
			"ch2613729" => "落第騎士の英雄譚（キャバルリィ）",
			"ch2614640" => "ランス・アンド・マスクス",
			//2016/winter
			"ch2617580" => "おじさんとマシュマロ",
			"ch2617720" => "シュヴァルツェスマーケン",
			"ch2617613" => "デュラララ!!×2 結",
			"ch2617616" => "プリンス・オブ・ストライド オルタナティブ",
			"ch2617714" => "無彩限のファントム・ワールド",
			"ch2617552" => "蒼の彼方のフォーリズム",
			"ch2617554" => "赤髪の白雪姫 2ndシーズン",
			"ch2618272" => "アクティヴレイド -機動強襲室第八係-",
			"ch2617581" => "大家さんは思春期！",
			"ch2617618" => "おしえて！ ギャル子ちゃん",
			"ch2617834" => "血液型くん！4期",
			"ch2617614" => "紅殻のパンドラ",
			"ch2617611" => "この素晴らしい世界に祝福を！",
			"ch2617321" => "最弱無敗の神装機竜《バハムート》",
			"ch2617587" => "少女たちは荒野を目指す",
			"ch2617094" => "SUSHI POLICE",
			"ch2617617" => "石膏ボーイズ",
			"ch2597821" => "生誕20周年ビーストウォーズ復活祭への道",
			"ch2618228" => "だがしかし",
			"ch2618231" => "旅街レイトショー",
			"ch2617491" => "ディバインゲート",
			"ch2617816" => "てーきゅう 7期",
			"ch2617594" => "Dimension W [ディメンション  ダブリュー]",
			"ch2617132" => "ナースウィッチ小麦ちゃんＲ",
			"ch2617386" => "虹色デイズ",
			"ch2617553" => "ノルン+ノネット",
			"ch2617493" => "灰と幻想のグリムガル",
			"ch2617612" => "ハルチカ-ハルタとチカは青春する-",
			"ch2617313" => "ファンタシースターオンライン2 ジ アニメーション",
			"ch2617615" => "ブブキ・ブランキ",
			"ch2617860" => "魔法少女なんてもういいですから。",
			"ch2617482" => "闇芝居 三期",
			"ch2617352" => "霊剣山　星屑たちの宴",
			//2016/spring
			"ch2620298" => "うさかめ",
			"ch2614001" => "学戦都市アスタリスク 2nd Season",
			"ch2620384" => "キズナイーバー",
			"ch2620924" => "三者三葉",
			"ch2620827" => "はいふり",
			"ch2617474" => "薄桜鬼～御伽草子～",
			"ch2620187" => "ハンドレッド",
			"ch2620673" => "Re:ゼロから始める異世界生活",
			"ch2620883" => "アルティメット・スパイダーマン VS シニスター・シックス",
			"ch2620642" => "あんハピ♪",
			"ch2620164" => "宇宙パトロールルル子",
			"ch2620083" => "エンドライド",
			"ch2620289" => "鬼斬",
			"ch2619536" => "影鰐-KAGEWANI-承",
			"ch2615179" => "カードファイト!! ヴァンガードG ストライド ゲート編",
			"ch2621363" => "牙狼＜GARO＞-魔戒烈伝-",
			"ch2620160" => "境界のRINNE 第2シリーズ",
			"ch2621300" => "クレーンゲール",
			"ch2620641" => "コンクリート・レボルティオ-超人幻想-THE LAST SONG",
			"ch2620675" => "くまみこ",
			"ch2620359" => "デュエル・マスターズ VSRF",
			"ch2621468" => "とんかつDJアゲ太郎",
			"ch2621253" => "坂本ですが？",
			"ch2620676" => "少年アシベ GO！GO！ゴマちゃん",
			"ch2620540" => "少年メイド",
			"ch2620801" => "ジョジョの奇妙な冒険 ダイヤモンドは砕けない",
			"ch2621080" => "聖戦ケルベロス 竜刻のファタリテ",
			"ch2620421" => "双星の陰陽師",
			"ch2620680" => "ジョーカーゲーム",
			"ch2620803" => "テラフォーマーズ リベンジ",
			"ch2620679" => "ネトゲの嫁は女の子じゃないと思った？",
			"ch2620364" => "パンでPeace！",
			"ch2620843" => "美少女戦士セーラームーンCrystal 3期＜デス・バスターズ編＞",
			"ch2620865" => "秘密結社 鷹の爪 GT",
			"ch2620677" => "文豪ストレイドッグス",
			"ch2620795" => "僕のヒーローアカデミア",
			"ch2620678" => "ビッグオーダー",
			"ch2621123" => "ばくおん!!",
			"ch2606891" => "プリパラ 3rd season",
			"ch2621276" => "プリパラ 3rd season",
			"ch2620840" => "ふらいんぐうぃっち",
			"ch2620664" => "迷家-マヨイガ-",
			"ch2620539" => "ラグナストライクエンジェルズ",
			"ch2620640" => "ワガママハイスペック",
			//2016/summer
			"ch2603486" => "アイカツ！チャンネル",
			"ch2623552" => "なりあ☆がーるずチャンネル",
			"ch2623557" => "ラブライブ！サンシャイン!!",
			"ch2623562" => "テイルズ オブ ゼスティリア ザ クロス",
			"ch2618272" => "アクティヴレイド -機動強襲室第八係- 2nd",
			"ch2623136" => "あまんちゅ！",
			"ch2623590" => "この美術部には問題がある！",
			"ch2623598" => "D.Gray-man HALLOW",
			//"ch2623420" => "TVアニメ「Rewrite」",
			"ch2623373" => "ばなにゃ",
			"ch2623734" => "美男高校地球防衛部LOVE！LOVE！",
			"ch2623597" => "B-PROJECT～鼓動＊アンビシャス～",
			"ch2623134" => "腐男子高校生活",
			"ch2623423" => "甘々と稲妻",
			"ch2623425" => "アルスラーン戦記 風塵乱舞",
			"ch2623454" => "アンジュ・ヴィエルジュ",
			"ch2623372" => "OZMAFIA!!",
			"ch2623483" => "クオリディア・コード",
			"ch2623264" => "SERVAMP -サーヴァンプ-",
			"ch2623958" => "Thunderbolt Fantasy 東離劍遊紀",
			"ch2623547" => "SHOW BY ROCK!!しょ～と!!",
			"ch2623561" => "食戟のソーマ 弐ノ皿",
			"ch2623551" => "スカーレッドライダーゼクス",
			"ch2623767" => "タイムトラベル少女～マリ・ワカと8人の科学者たち～",
			"ch2623666" => "タブー・タトゥー",
			"ch2623563" => "チア男子!!",
			"ch2623959" => "ツキウタ。 THE ANIMATION",
			"ch2623599" => "DAYS",
			"ch2605854" => "トランスフォーマーアドベンチャー　-マイクロンの章-",
			"ch2624033" => "91Days",
			"ch2623457" => "NEW GAME!",
			"ch2623669" => "ねじ巻き精霊戦記 天鏡のアルデラミン",
			"ch2623546" => "パズドラクロス",
			"ch2623766" => "初恋モンスター",
			"ch2623549" => "はんだくん",
			"ch2623456" => "Fate/kaleid liner プリズマ☆イリヤ ドライ!!",
			"ch2623445" => "不機嫌なモノノケ庵",
			"ch2621033" => ",planetarian",
			"ch2623548" => "ベルセルク",
			"ch2623455" => "魔装学園H×H",
			"ch2623559" => "モブサイコ100 ",
			"ch2623249" => "ReLIFE",
			"ch2623453" => "レガリア The Three Sacred Stars",
			//2016/autumn
			"ch2625890" => "うたの☆プリンスさまっ♪マジLOVEレジェンドスター",
			"ch2626328" => "刀剣乱舞-花丸-",
			"ch2626226" => "夏目友人帳 伍",
			"ch2619248" => "学園ハンサム",
			"ch2626387" => "あおおに　-じ・あにめぇしょん-",
			"ch2626302" => "アイドルメモリーズ",
			"ch2625211" => "３ねんＤぐみガラスの仮面",
			"ch2625964" => "はがねオーケストラ",
			"ch2626230" => "あにトレ！XX～ひとつ屋根の下で～",
			"ch2626332" => "TVアニメ「ALL OUT!!」",
			"ch2626227" => "Occultic；Nine -オカルティック・ナイン-",
			"ch2625693" => "おくさまが生徒会長!+! ",
			"ch2626240" => "カードファイト!! ヴァンガードG NEXT",
			"ch2626369" => "チーティングクラフト ",
			"ch2625892" => "てーきゅう　8期",
			"ch2626368" => "TO BE HERO",
			"ch2626334" => "TRICKSTER -江戸川乱歩「少年探偵団」より-",
			"ch2626182" => "ナゾトキネ",
			"ch2625694" => "バーナード嬢曰く。",
			"ch2625756" => "響け！ユーフォニアム2",
			"ch2626124" => "魔法少女なんてもういいですから。セカンドシーズン",
			"ch2626183" => "WWW.WORKING!!",
			"ch2626447" => "雨色ココア in Hawaii",
			"ch2625755" => "うどんの国の金色毛鞠",
			"ch2626120" => "怪獣娘-ウルトラ怪獣擬人化計画-",
			"ch2625728" => "拡張少女系トライナリー",
			"ch2626337" => "ガーリッシュ ナンバー",
			"ch2625596" => "奇異太郎少年の妖怪絵日記",
			"ch2626443" => "クレーンゲール Galaxy",
			"ch2626180" => "競女!!!!!!!!",
			"ch2626360" => "灼熱の卓球娘",
			"ch2626184" => "終末のイゼッタ",
			"ch2626121" => "SHOW BY ROCK!!#",
			"ch2626019" => "ステラのまほう",
			"ch2626331" => "装神少女まとい",
			"ch2626321" => "侍霊演武：将星乱",
			"ch2626333" => "タイガーマスクＷ",
			"ch2626241" => "デジモンユニバース アプリモンスターズ",
			"ch2625959" => "ナンバカ",
			"ch2626352" => "にゃんぼー！",
			"ch2625889" => "信長の忍び",
			"ch2626327" => "ハイキュー!! 烏野高校 ＶＳ 白鳥沢学園高校",
			"ch2626022" => "ブブキ・ブランキ 星の巨人",
			"ch2626299" => "ブラッディヴォ―レス",
			"ch2625760" => "フリップフラッパーズ",
			"ch2626023" => "ブレイブウィッチーズ",
			"ch2620677" => "文豪ストレイドッグス",
			"ch2626359" => "マーベル アベンジャーズ・アッセンブル",
			"ch2626329" => "マジきゅんっ！ルネッサンス",
			"ch2626300" => "魔法少女育成計画",
			"ch2626534" => "ユーリ!!! on ICE",
			"ch2626179" => "Lostorage incited WIXOSS",
			"ch2626303" => "私がモテてどうすんだ",
			"ch2626775" => "月曜日のたわわ",
			//2017/winter
			"ch2623562" => "テイルズ オブ ゼスティリア ザ クロス",
			"ch2628485" => "セイレン",
			"ch2628841" => "闇芝居 四期",
			"ch2627896" => "超・少年探偵団NEO",
			"ch2628359" => "風夏",
			"ch2628257" => "あいまいみー～Surgical Friends～",
			"ch2628659" => "銀魂．",
			"ch2628459" => "ちるらん にぶんの壱",
			"ch2628133" => "SUPER LOVERS 2",
			"ch2628610" => "エルドライブ",
			"ch2628810" => "刀剣乱舞　おっきいこんのすけの刀剣散歩",
			"ch2628611" => "にゃんこデイズ",
			"ch2628486" => "ピアシェ～私のイタリアン～",
			"ch2628353" => "アイドル事変",
			"ch2628127" => "ハンドシェイカー",
			"ch2628132" => "この素晴らしい世界に祝福を！2",
			"ch2628658" => "うらら迷路帖",
			"ch2627905" => "弱虫ペダル NEW GENERATION",
			"ch2628125" => "One Room",
			"ch2628162" => "政宗くんのリベンジ",
			"ch2628126" => "幼女戦記",
			"ch2628239" => "霊剣山 叡智への資格",
			"ch2628354" => "けものフレンズ",
			"ch2628488" => "南鎌倉高校女子自転車部",
			"ch2628857" => "神々の記",
			"ch2628355" => "AKIBA’S TRIP -THE ANIMATION- ",
			"ch2628128" => "CHAOS;CHILD",
			"ch2628246" => "小林さんちのメイドラゴン",
			"ch2628318" => "スクールガールストライカーズ",
			"ch2628609" => "SPIRITPACT スピリットパクト",
			"ch2623420" => "TVアニメ「Rewrite」",
			"ch2628607" => "戦隊ヒーロー スキヤキフォース",
			"ch2628131" => "ガヴリールドロップアウト", 
			// 2017/spring
			"ch2630745" => "デュエル・マスターズ(2017)",
			"ch2620795" => "僕のヒーローアカデミア",
			"ch2630156" => "TVアニメ「進撃の巨人」Season 2",
			"ch2629876" => "信長の忍び-伊勢・金ヶ崎篇-",
			"ch2623548" => "ベルセルク",
			"ch2630712" => "兄に付ける薬はない！",
			"ch2630568" => "武装少女マキャヴェリズム",
			"ch2629876" => "BORUTO-ボルト-",
			"ch2630748" => "境界のＲＩＮＮＥ（第３シリーズ）",
			"ch2630626" => "世界の闇図鑑",
			"ch2630747" => "アトム ザ・ビギニング",
			"ch2630541" => "アキンド星のリトル・ペソ",
			"ch2630570" => "ゼロから始める魔法の書",
			"ch2630571" => "ツインエンジェルＢＲＥＡＫ",
			"ch2630529" => "スタミュ(第2期)",
			"ch2630256" => "GRANBLUE FANTASY The Animation",
			"ch2629853" => "ソード・オラトリア",
			"ch2630719" => "笑ゥせぇるすまんNEW",
			"ch2630538" => "クロックワーク・プラネット",
			"ch2630573" => "エロマンガ先生",
			"ch2630718" => "夏目友人帳 陸",
			"ch2630404" => "王室教師ハイネ",
			"ch2630892" => "喧嘩番長 乙女",
			"ch2630566" => "終末なにしてますか？忙しいですか？救ってもらっていいですか？",
			"ch2630797" => "スナックワールド",
			"ch2630528" => "正解するカド",
			"ch2630762" => "Room Mate",
			"ch2630687" => "TVアニメ「つぐもも」",
			"ch2630737" => "恋愛暴君",
			"ch2630539" => "アイドルタイムプリパラ",
			"ch2629886" => "フレームアームズ・ガール",
			"ch2630569" => "カブキブ！",
			"ch2630398" => "TVアニメ「サクラクエスト」",
			"ch2630713" => "有頂天家族２",
			"ch2630400" => "サクラダリセット",
			"ch2630595" => "まけるな！！あくのぐんだん！",
			"ch2630746" => "ラブ米　-WE LOVE RICE-",
			"ch2630572" => "ひなこのーと",
			"ch2630540" => "sin 七つの大罪",
			"ch2630349" => "覆面系ノイズ",
			"ch2630711" => "銀の墓守り",
			"ch2630567" => "ロクでなし魔術講師と禁忌教典",
			"ch2630581" => "夢王国と眠れる100人の王子様 ショート",
			// 2017/summer
			"ch2632337" => "地獄少女 宵伽",
			"ch2632480" => "ナイツ＆マジック",
			"ch2632360" => "闇芝居 五期",
			"ch2629114" => "最遊記RELOAD BLAST",
			"ch2632187" => "天使の３Ｐ！",
			"ch2632324" => "セントールの悩み",
			"ch2632242" => "無責任ギャラクシー☆タイラー",
			"ch2632445" => "ウルトラマンジード",
			"ch2632267" => "恋と嘘",
			"ch2632188" => "NEW GAME!!",
			"ch2632193" => "ようこそ実力至上主義の教室へ",
			"ch2632461" => "潔癖男子！青山くん",
			"ch2632230" => "時間の支配者",
			"ch2632466" => "ナナマル サンバツ",
			"ch2632446" => "ひなろじ -from Luck & Logic-",
			"ch2632613" => "プリンセス・プリンシパル",
			"ch2632260" => "妖怪アパートの幽雅な日常",
			"ch2632351" => "イケメン戦国◆時をかけるが恋ははじまらない",
			"ch2632653" => "ノラと皇女と野良猫ハート",
			"ch2632191" => "バチカン奇跡調査官",
			"ch2632189" => "はじめてのギャル",
			"ch2632133" => "捏造トラップ-NTR-",
			"ch2632444" => "カイトアンサ",
			"ch2632268" => "活撃 刀剣乱舞",
			"ch2632213" => "てーきゅう　9期",
			"ch2632261" => "異世界はスマートフォンとともに。",
			"ch2632467" => "コンビニカレシ",
			"ch2632468" => "アクションヒロイン チアフルーツ",
			"ch2632190" => "メイドインアビス",
			"ch2632363" => "ひとりじめマイヒーロー",
			"ch2632397" => "異世界食堂",
			"ch2632399" => "戦姫絶唱シンフォギアAXZ",
			"ch2632339" => "縁結びの妖狐ちゃん",
			"ch2632243" => "18if",
			"ch2632265" => "徒然チルドレン",
			"ch2632264" => "アホガール",
			"ch2632440" => "スカートの中はケダモノでした｡",
			// 2017/autumn
			"ch2633651" => "十二大戦",
			"ch2633614" => "Code：Realize 創世の姫君",
			"ch2633566" => "ネト充のススメ",
			"ch2628659" => "銀魂．",
			"ch2633715" => "ブレンド・S",
			"ch2633508" => "「鬼灯の冷徹」第弐期",
			"ch2633776" => "王様ゲーム The Animation",
			"ch2633629" => "宝石の国",
			"ch2633804" => "アイドルマスター シンデレラガールズ劇場　2期",
			"ch2633809" => "Infini-T Force",
			"ch2633602" => "血界戦線 & BEYOND",
			"ch2633834" => "UQ HOLDER! 魔法先生ネギま！2",
			"ch2633647" => "URAHARA",
			"ch2633635" => "干物妹！うまるちゃんR",
			"ch2633866" => "妹さえいればいい。",
			"ch2633837" => "Dies irae",
			"ch2633810" => "俺たちゃ妖怪人間",
			"ch2633626" => "つうかあ",
			"ch2633855" => "DYNAMIC CHORD",
			"ch2633243" => "キノの旅 -the Beautiful World- the Animated Series",
			"ch2633852" => "刀剣乱舞 おっきいこんのすけの刀剣散歩　弐",
			"ch2633803" => "ラブライブ！サンシャイン!!TVアニメ2期",
			"ch2633643" => "「おそ松さん」第2期",
			"ch2633245" => "このはな綺譚",
			"ch2633868" => "aiseki MOGOL GIRL",
			"ch2633913" => "あめこん!!",
			"ch2633856" => "アニメガタリズ",
			"ch2633802" => "アイドルマスター SideM",
			"ch2633720" => "テレビアニメ「ブラッククローバー」",
			"ch2629876" => "",
			// 2018/winter
			"ch2634955" => "刀使ノ巫女",
			"ch2634868" => "ゆるキャン△",
			"ch2634961" => "弱虫ペダル GLORY LINE",
			"ch2634164" => "アイドリッシュセブン",
			"ch2634750" => "グランクレスト戦記",
			"ch2634670" => "まめねこ",
			"ch2634849" => "スロウスタート",
			"ch2634794" => "ダーリン・イン・ザ・フランキス",
			"ch2634472" => "働くお兄さん！",
			"ch2634795" => "七つの大罪 戒めの復活",
			"ch2634763" => "だがしかし２",
			"ch2634764" => "たくのみ。",
			"ch2634616" => "オーバーロードII",
			"ch2634856" => "サンリオ男子",
			"ch2634962" => "りゅうおうのおしごと！",
			"ch2634855" => "宇宙よりも遠い場所",
			"ch2634841" => "学園ベビーシッターズ",
			"ch2634877" => "カードキャプターさくら クリアカード編",
			"ch2634960" => "アニメ 続『刀剣乱舞-花丸-』",
			"ch2634923" => "メルヘン・メドヘン",
			"ch2634620" => "三ツ星カラーズ",
			"ch2634998" => "からかい上手の高木さん",
			"ch2634924" => "gdメン",
			"ch2634990" => "ラーメン大好き小泉さん",
			// 2018/spring
			"ch2636322" => "こみっくがーるず",
			"ch2636268" => "TVアニメ「ペルソナ５」",
			"ch2636105" => "Caligula-カリギュラ-",
			"ch2633508" => "「鬼灯の冷徹」第弐期",
			"ch2636032" => "ハイスクールD×D HERO",
			"ch2636065" => "あまんちゅ！ あどばんす",
			"ch2636132" => "東京喰種:re",
			"ch2636136" => "ルパン三世 PART5",
			"ch2636334" => "ありすorありす",
			"ch2636133" => "多田くんは恋をしない",
			"ch2636267" => "ラストピリオド ‐終わりなき螺旋の物語‐",
			"ch2636308" => "パズドラ",
			"ch2636033" => "シュタインズ・ゲート ゼロ",
			"ch2636323" => "魔法少女 俺",
			"ch2636433" => "キャプテン翼",
			"ch2636321" => "されど罪人は竜と踊る",
			"ch2636146" => "メガロボクス",
			"ch2636333" => "３Ｄ彼女　リアルガール",
			"ch2636222" => "あっくんとカノジョ",
			"ch2634623" => "Butlers 千年百年物語",
			"ch2636035" => "ヒナまつり",
			"ch2636034" => "フルメタル・パニック！ Invisible Victory",
			"ch2636115" => "ウマ娘 プリティーダービー",
			"ch2636138" => "信長の忍び-姉川・石山篇-",
			"ch2636031" => "ソードアート・オンライン オルタナティブ ガンゲイル・オンライン",
			"ch2636335" => "ニル・アドミラリの天秤",
			"ch2636273" => "デュエル・マスターズ！",
			"ch2636257" => "銀河英雄伝説 Die Neue These",
			"ch2636309" => "デビルズライン",
			"ch2636208" => "美男高校地球防衛部HAPPY KISS！",
			"ch2635845" => "甘い懲罰 私は看守専用ペット",


			// user
			// 公式
			"20710533" => "ニコニコ静画さん",
			// その他
			"42016" => "けいえむ(K.M)さん",
			// 演奏してみた
			"2765486" => "まらしぃさん",
			"1681659" => "へっぽこ六弦使いさん",
			// 動画系
			"1274133" => "わかむらP",
			"10772149" => "三重の人さん",
			"6795814" => "まさたかさん",
			"1701104" => "えろ豆さん",
			"5776736" => "ミロさん",
			"3251290" => "アノマロさん",
			"876644" => "ブラザーさん",
			"5931222" => "ミスるあP",
			"4011478" => "cortさん",
			"454965" => "Latさん",
			"661114" => "inumaruさん",
			"1812449" => "Aono.Yさん",
			// VOCALOID系
			"325945" => "ゆうゆさん",
			"308936" => "ふわふわシナモンさん(OSTER project)",
			"1264536" => "Re:nGさん",
			"1923193" => "bakerさん",
			"101303" => "19さん(19's Sound Factory)",
			"1091989" => "cosMoさん(暴走P)",
			"531122" => "t.Komineさん(うたたP)",
			"1158153" => "くちばしさん",
			"2523470" => "ラマーズP",
			"7032706" => "OneRoomさん(ジミーサムP)",
			"1159685" => "HiroyukiOdaさん(鼻そうめんP)",
			"461870" => "sasakure.UKさん",
			"527473" => "yuukissさん",
			"1653063" => "ココアシガレットP",
			"9450851" => "Treowさん(逆衝動P)",
			"161775" => "へっどほんトーキョーさん(とくP)",
			"161834" => "SHIKIさん",
			"4632758" => "Diosさん/シグナルP",
			"2476652" => "SAMさん(samfree)",
			"1313476" => "Clean Tearsさん",
			"1135460" => "ヽ(ヽ･∀∀･)にこP",
			"2566558" => "otetsuさん",
			"149873" => "KotsBeirneさん(骨盤P)",
			"3343223" => "40㍍P",
			"9718519" => "古墳P",
			"1697809" => "shu-tさん",
			"4039104" => "U-skeさん",
			"1745804" => "ryuryuさん(びにゅP)",
			"1839939" => "M@SATOSHIさん(あルカP)",
			"1414235" => "ちゃぁさん",
			"2423537" => "BETTIさん(EasyPopさん)",
			"361309" => "Aether_Eruさん(P∴Rhythmatiq)",
			"2151573" => "miumixさん",
			"8355166" => "riverさん",
			"144833" => "虹原ぺぺろんさん",
			"11587180" => "ミュームさん(ミュムP)",
			"3706808" => "U-jiさん(霊長類P)",
			"5352709" => "sunzriverさん(すんｚりヴぇｒP)",
			"11654053" => "チータンさん",
			"7622467" => "ざにおさん(パイパンP)",
			"2165946" => "Rinさん(ぎんすけさん)",
			"8813678" => "←P",
			"15104449" => "8#Princeさん(八王子P)",
			"415080" => "Junkさん(定額P)",
			"175043" => "voidさん",
			"1068149" => "P*Lightさん",
			"388593" => "stereoberryさん",
			"31008" => "よしのさん",
			"217043" => "fmy.さん",
			"12190220" => "工藤ザンギエフさん(dokuP)",
			"18175484" => "rlboroさん",
			"1136553" => "のぼる↑さん",
			"865591" => "ピノキオP",
			"8785358" => "すこっぷさん",
			"669463" => "millstonesさん",
			"1169727" => "Dixie Flatlineさん",
			"6630202" => "ずどどんさん",
			"793778" => "そそそさん",
			"797416" => "Tripshotsさん",
			"12960983" => "たーP",
			"13468933" => "0108-音屋-さん",
			"735743" => "kiichiさん(なんとかP)",
			"603250" => "曲者さん",
			"6548559" => "恋竹林さん",
			"10553903" => "アウトプットP",
			"1480332" => "におさん",
			"11543875" => "Keroliane Agehaさん",
			"10985137" => "ちばけんいちさん(味噌汁P)",
			"278512" => "KTGさん(チーターガールP)",
			"10043504" => "KITさん",
			"3967970" => "Emonさん(Tes.)",
			"2581746" => "DATEKENさん",
			"1375856" => "yusukeP",
			"2538001" => "SmileRさん",
			"33280" => "なかさん(チーズP)",
			"882099" => "effeさん",
			"2545809" => "シャガールさん(ワールドリバースP)",
			"11701964" => "無力P",
			"9233949" => "colateさん",
			"3492915" => "瑞智士記さん",
			"508944" => "tehaさん",
			"2591026" => "れるりりさん",
			"7550057" => "KulfiQさん",
			"595445" => "Last Note.さん",
			"865371" => "koyoriさん(電ポルP)",
			"582229" => "164さん",
			"380847" => "ハチさん",
			"449061" => "黒うささん",
			"811012" => "DECO*27さん",
			"11912389" => "wowakaさん",
			"15872264" => "じんさん(自然の敵P)",
			"24550889" => "kemuさん",
			"11314366" => "Junkyさん",
			"22580750" => "Mitchie Mさん",
			// image
			"19186251" => "のうさん",
			"17586868" => "のちたしんさん",
			"577991" => "ソラさん",
			"237872" => "住咲ゆづなさん",
			"13760803" => "悠理さん",
			"10647" => "九十九さん",
			"1266839" => "モフP",
			"19708656" => "さきの新月さん",
			// self
			"1380789" => "Mint=Rabbit",
			);
?>
