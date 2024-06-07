<?php
$path = "/";
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="Play minecraft 1.8 in your browser" />
		<meta name="keywords" content="eaglercraft, eaglercraftx, minecraft, 1.8, 1.8.8" />
		<title>Minecraft</title>
		<meta property="og:locale" content="en-US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="Minecraft" />
		<meta property="og:description" content="Play minecraft 1.8 in your browser" />
		<meta property="og:image" content="<?= $path; ?>favicon.png" />
		<link type="image/png" rel="shortcut icon" href="<?= $path; ?>favicon.png" />
		<script type="text/javascript" src="<?= $path; ?>classes.js"></script>
		<script type="text/javascript">
			"use strict";
			window.addEventListener("load", () => {
				if(document.location.href.startsWith("file:")) {
					alert("HTTP please, do not open this file locally, run a local HTTP server and load it via HTTP");
				}else {
					
					// %%%%%%%%% launch options %%%%%%%%%%%%
					
					const relayId = Math.floor(Math.random() * 3);
					window.eaglercraftXOpts = {
						demoMode: false,
						container: "game_frame",
						assetsURI: "<?= $path; ?>assets.epk",
						localesURI: "lang/",
						worldsDB: "worlds",
						servers: [
							 { addr: "wss://192.168.76.111/server", name: "Primary Server" } 
						],
						relays: [
							{ addr: "wss://relay.deev.is/", comment: "lax1dude relay #1", primary: relayId == 2 },
							{ addr: "wss://relay.lax1dude.net/", comment: "lax1dude relay #2", primary: relayId == 1 },
							{ addr: "ws://127.0.0.1:6699", comment: "Primary Relay", primary: relayId == 0 }
						]
					};
					
					// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
					
					var q = window.location.search;
					if(typeof q === "string" && q.startsWith("?")) {
						q = new URLSearchParams(q);
						var s = q.get("server");
						if(s) window.eaglercraftXOpts.joinServer = s;
					}
					
					main();
					
				}
			});
		</script>
	</head>
	<body style="margin:0px;width:100vw;height:100vh;overflow:hidden;" id="game_frame">
	</body>
</html>