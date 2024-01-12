<template>
	<!-- <div class="modal fade" id="imageViewer" tabindex="-1" role="dialog" aria-labelledby="imageViewer" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <button @click="closeImageViewer()" type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:absolute;top:0 px;z-index:999;right:0;padding:0 px 10 px;background:#000;color:#fff;font-size:20px">
          <span aria-hidden="true">&times;</span>
        </button>
          <img style="max-height: 100%;width:auto" src="">
      </div>
    </div>
  </div> -->

	<div
		class="modal fade"
		id="imageViewer"
		tabindex="-1"
		aria-labelledby="imageViewerLabel"
		aria-hidden="true"
	>
		<div class="modal-dialog modal-dialog-scrollable modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="imageViewerLabel">Image Viewer</h5>
					<button
						@click="closeImageViewer()"
						type="button"
						class="close"
						data-dismiss="modal"
						aria-label="Close"
					>
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<img src="" alt="" style="width: 100%" />
				</div>
				<div class="modal-footer">
					<button
						type="button"
						class="btn btn-secondary"
						data-dismiss="modal"
						@click="closeImageViewer()"
					>
						Close
					</button>
					<!-- <button type="button" class="btn btn-primary">Save changes</button> -->
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		name: "ImageViewer",
		methods: {
			closeImageViewer() {
				var modal = document.getElementById("imageViewer");
				var imageSRC = document.querySelector("#imageViewer modal-body img");
				modal.style.display = "none";
				imageSRC.src = "";
			},
		},
	};
</script>

<style scoped></style>
