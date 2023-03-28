<!-- Highscores Widget -->
<aside class="single_sidebar_widget search_widget">
	<form action="highscores.php" method="get">
		<h4 class="widget_title">Highscores</h4>
		<p>Select skill type to view:</p>
		<div class="input-group-icon mt-10">
			<div class="form-group">
				<div class="default-select" id="default-select_2">
					<select name="type">
						<option value="7">Experience</option>
						<option value="5">Shielding</option>
						<option value="3">Axe</option>
						<option value="2">Sword</option>
						<option value="1">Club</option>
						<option value="4">Distance</option>
						<option value="9">Fist</option>
						<option value="6">Fish</option>
						<option value="8">Magic</option>
					</select>
				</div>
			</div>
		</div>
		<button class="button rounded-0 primary-bg text-white w-100 btn_1" type="submit">Fetch scoreboard</button>
	</form>
</aside>

