			</div>
		</div>

		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

		<!-- Chart.js -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js"></script>

		<!-- Custom JavaScript -->
		<?
		function json_safe_encode($data){
			return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
		}

		if(isset($loadJavaScriptList)){
			foreach($loadJavaScriptList as $javaScript)
			{
				$name = explode(".", $javaScript['name'])[0];
				$params = json_encode($javaScript['params']);
				echo "<script>var param_${name} = JSON.parse('${params}'.replace(/\\r?\\n/g, '<br>').replace(/\\t/g, '&nbsp;&nbsp;&nbsp;&nbsp;'));</script>";
				//echo "<script>var param_${name} = JSON.parse('${params}'.replace(/(\\r\\n)/g, '\\\\n'));</script>";
				echo "<script id=\"${javaScript['name']}\" src=\"${javaScript['name']}\"></script>";
			}
		}
		?>
	</body>
</html>
