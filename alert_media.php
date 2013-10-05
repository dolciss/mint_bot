<?php
// alert_live.php
// ニコニコアラートのチェックする動画・静画

$medilist = array(
			// 接尾辞(sm/nm/im/mg)除く
			"3000000", // im
			"20000000", // sm
			);
$userlist = array(
			// channel
			"ch253" => "初音ミク ―Project DIVA―",
			"ch41" => "GINGA",
			"ch765" => "ニコニコアイマスｃｈ“たるき亭”",
			"ch203" => "Lantisちゃんねる",

			// anime
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
			"ch2579855" => "せかつよチャンネル",
			"ch2582357" => "ダイヤのA",
			"ch2581926" => "てーきゅう３期チャンネル",
			"ch2580972" => "DIABOLIK LOVERS",
			"ch2576710" => "てさぐれ！部活もの",
			"ch2581937" => "東京レイヴンズ",
			"ch2581384" => "凪のあすから",
			"ch2581583" => "のんのんびより",
			"ch2581671" => "pupaチャンネル",
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
			// image
			"19186251" => "のうさん",
			"17586868" => "のちたしんさん",
			"577991" => "ソラさん",
			"237872" => "住咲ゆづなさん",
			"13760803" => "悠理さん",
			"10647" => "九十九さん",
			"1266839" => "モフP",
			// self
			"1380789" => "Mint=Rabbit",
			);
?>
