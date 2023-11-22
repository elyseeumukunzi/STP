<?php
session_start();

//connexion à la base de données 
include("connect.php");
include("connectLangues.php");

$id=$_SESSION['id'];

//recherche des résultats dans la base de données

if(isset($_GET['date']))
{
	$result=$connexion->query( 'SELECT *FROM messages m WHERE m.datemessage LIKE \'%' . safe( $_GET['d'] ) . '%\'' );
}
 
if(isset($_GET['name']))
{
	$result=$connexion->query( 'SELECT *FROM utilisateurs u,patients p,messages m WHERE u.id_u=p.id_u AND m.senderId=p.u_id AND (u.full_name LIKE \'%' . safe( $_GET['n'] ) . '%\')' );
}
 
 
/*--------affichage d'un message "pas de résultats"------------*/

$billRows=$result->rowCount();
if( $billRows == 0 )
{
?>
    <h3 style="text-align:center; margin:10px 0 20px;background-color:black;color:white; padding:5px"><?php if(isset($_GET['name'])){ echo $_GET['n'];}if(isset($_GET['sn'])){ echo $_GET['s'];}if(isset($_GET['date'])){ echo $_GET['d'];}if(isset($_GET['bn'])){ echo $_GET['b'];}?><br/><span style="color:#bf0000"><?php echo getString(85)?></span></h3>
<?php
}else{
?>
	<table style="margin:auto;" cellspacing="0">
	<tr>	
<?php
    // parcours et affichage des résultats
	$result->setFetchMode(PDO::FETCH_OBJ);
	
	if(isset($_GET['date']))
	{
		while( $post = $result->fetch())
		{
?>
			<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
			<td id="<?php echo $post->id_message?>">
			
				<table cellspacing="10" style="border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
					
					<tr>
						<td>
							<a class="searchB" href="messages.php?id_message=<?php echo $post->id_message?>divMessage=ok">
								<h4><?php echo $post->objet.'<br/>('.$post->datemessage.')'; ?></h4>
							</a>
						</td>
					
					</tr>
					
				</table>
			</td>	
	<?php
		}
	}
	
	if(isset($_GET['name']))
	{
		while( $post = $result->fetch())
		{
?>
			<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
			<td id="<?php echo $post->id_message?>">
			
				<table cellspacing="10" style="border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
					
					<tr>
						<td>
							<a class="searchB" href="messages.php?idbill=<?php echo $post->id_message?>&divMessage=ok">
								<h4><?php echo $post->full_name.'<br/>'.$post->objet.'('.$post->datemessage.')'; ?></h4>
							</a>
						</td>
					
					</tr>
					
				</table>
			</td>	
	<?php
		}
	}
	
	if(isset($_GET['sn']))
	{
		while( $post = $result->fetch())
		{
?>
			<td><i class="fa fa-sort-up fa-fw fa-rotate-90"></i></td>
			<td id="<?php echo $post->id_bill?>">
			
				<table cellspacing="10" style="<?php if($post->status==0){?>background:rgba(0,0,0, 0.15); color:#777; <?php ;}else {?>background-color:#fff; color:#a00000;<?php ;}?>; border:1px solid #999; border-radius:3px; padding:2px 9px 2px 9px;">
					
					<tr>
						<td>
							<a class="<?php if($post->status==0){?>searchA<?php ;}else {?>searchB<?php ;}?>" href="listfacture.php?idbill=<?php echo $post->id_bill?>&codeCash=<?php echo $_GET['codeCash'];?>&divBill=ok">
								<h4><?php echo $post->full_name.'<br/>'.$post->numbill.'('.$post->datebill.')'; ?></h4>
							</a>
						</td>
					
					</tr>
					
				</table>
			</td>	
	<?php
		}
	}
	?>
	</tr>
	</table>
	
 <?php
}
 
/*****
fonctions
*****/
function safe($var)
{
	//$var = mysql_real_escape_string($var);
	$var = addcslashes($var, '%_');
	$var = trim($var);
	$var = htmlspecialchars($var);
	return $var;
}
?>