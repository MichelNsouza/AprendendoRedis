{ pkgs }: {
	deps = [
   pkgs.redis
   pkgs.php82Packages.composer
		pkgs.php82
	];
}