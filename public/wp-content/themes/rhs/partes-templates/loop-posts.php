<style type="text/css">
  .block {
  margin: 20px;
  border-radius: 4px;
  width: 100%;
  min-height: 642px;
  max-height: 642px;
  background: #fff;
  padding: 23px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
      -ms-flex-direction: column;
          flex-direction: column;
  box-shadow: 0 2px 55px rgba(0,0,0,0.1);
}
.top {
  border-bottom: 1px solid #e5e5e5;
  padding-bottom: 10px;
}
.top ul {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: justify;
      -ms-flex-pack: justify;
          justify-content: space-between;
}
.top a {
  color: #9e9e9e;
}
.top a:hover {
  color: #c7ccdb;
}
.converse {
  padding: 2px 10px;
  border-radius: 20px;
  text-transform: uppercase;
  font-size: 14px;
}
.middle {
  margin-bottom: 40px;
}
.middle img {
  width: 100%;
}
.bottom {
  text-align: center;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
      -ms-flex-direction: column;
          flex-direction: column;
  -webkit-box-pack: justify;
      -ms-flex-pack: justify;
          justify-content: space-between;
  -webkit-box-flex: 1;
      -ms-flex-positive: 1;
          flex-grow: 1;
}
.heading {
  font-size: 17px;
  text-transform: uppercase;
  margin-bottom: 5px;
  letter-spacing: 0;
}
.info {
  font-size: 14px;
  color: #969696;
  text-align: justify;
}
.style {
  font-size: 16px;
  margin-bottom: 20px;
}
.style img{
	border-radius: 50px;
	padding: 23%;
}
.style .user_box{
	padding-top: 20px;
	text-align: left;
}
.style .votebox{
	display: grid;
	padding-top: 20px;
}
.old-price {
  color: #f00;
  text-decoration: line-through;
}
</style>
<?php if(have_posts()) : ?>
	<div class="row">
		<?php
			while (have_posts()): 
				the_post();

				//Pega o paineldosposts para mostrar na pagina front-page os posts.
				get_template_part( 'partes-templates/post');
			endwhile;	
		?>
	</div><!--display-row-->
	<div class="row">
		<div class="col-xs-12">
			<div class="text-center">
				<?php paginacao_personalizada(); ?>
			</div>
		</div>
	</div>
	<?php
		else :
			get_template_part('partes-templates/none'); 
	?>
<?php endif; ?>