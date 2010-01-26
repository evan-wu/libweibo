<?php
/* LibWeibo
 * a simpla php class for sina weibo open api
 * 
 * @author: easychen@gmail.com
 */


/*
 * How to use?
 *
 * $w = new weibo( 'APP Key' );
 * $w->setUser( 'username' , 'password' );
 * print_r($w->public_timeline());
 *
*/


class weibo 
{
	function __construct( $akey , $skey = '' ) 
	{
		$this->akey = $akey;
		$this->skey = $skey;
		$this->base = 'http://api.t.sina.com.cn/';
		$this->curl = curl_init();
		curl_setopt( $this->curl , CURLOPT_RETURNTRANSFER, true); 
		
		$this->postdata[] = 'source=' . $this->akey;
		
	}

	function setUser( $name , $pass ) 
	{
		$this->user['name'] = $name;
		$this->user['pass']  = $pass;
		curl_setopt( $this->curl , CURLOPT_USERPWD , "$name:$pass" );
	}

	function public_timeline()
	{
		return $this->call_method( 'statuses' , 'public_timeline' );
	}
	
	function friends_timeline()
	{
		return $this->call_method( 'statuses' , 'friends_timeline' );
	}
	
	function user_timeline( $name ) 
	{
		return $this->call_method( 'statuses' , 'user_timeline' , '?screen_name=' . urlencode( $name ) );
	}
	
	function mentions( $count = 10 , $page = 1 ) 
	{
		return $this->call_method( 'statuses' , 'mentions' , '?count=' . $count . '&page=' , $page  );
	}
	
	function comments_timeline( $count = 10 , $page = 1 )
	{
		return $this->call_method( 'statuses' , 'comments_timeline' , '?count=' . $count . '&page=' , $page  );
	}
	
	function comments_by_me( $count = 10 , $page = 1 )
	{
		return $this->call_method( 'statuses' , 'comments_by_me' , '?count=' . $count . '&page=' , $page  );
	}
	
	function comments( $tid , $count = 10 , $page = 1 )
	{
		return $this->call_method( 'statuses' , 'comments' , '?id=' . $tid . '&count=' . $count . '&page=' , $page  );
	}
	
	function counts( $tids )
	{
		return $this->call_method( 'statuses' , 'counts' , '?tids=' . $tids   );
	}
	
	function show( $tid )
	{
		return $this->call_method( 'statuses' , 'show/' . $tid  );
	}
	
		function destroy( $tid )
	{
	
		//curl_setopt( $this->curl , CURLOPT_CUSTOMREQUEST, "DELETE"); 		
		return $this->call_method( 'statuses' , 'destroy/' . $tid  );
	}
	
	
	function repost( $tid , $status )
	{
		$this->postdata[] = 'id=' . $tid;
		$this->postdata[] = 'status=' . urlencode($status);
		return $this->call_method( 'statuses' , 'repost'  );
	}
	
	
	function update( $status )
	{
		$this->postdata[] = 'status=' . urlencode($status);
		return $this->call_method( 'statuses' , 'update'  );
	}
	
	function upload( $status , $file )
	{
		
		curl_setopt( $this->curl , CURLOPT_POST , 1 );
		$this->postdata[] = 'status=' . urlencode( $status );
		$this->postdata[] = "pic=@".$file ;
		
		return $this->call_method( 'statuses' , 'update'  );
	}
	
	function send_comment( $tid , $comment , $cid = '' )
	{
		$this->postdata[] = 'id=' . $tid;
		$this->postdata[] = 'comment=' . urlencode($comment);
		if( intval($cid) > 0 ) $this->postdata[] = 'cid=' . $cid;
		return $this->call_method( 'statuses' , 'comment'  );
	}
	
	function reply( $tid , $reply , $cid  )
	{
		$this->postdata[] = 'id=' . $tid;
		$this->postdata[] = 'comment=' . urlencode($comment);
		if( intval($cid) > 0 ) $this->postdata[] = 'cid=' . $cid;
		return $this->call_method( 'statuses' , 'comment'  );
	}
	
	function remove_comment( $cid )
	{
		return $this->call_method( 'statuses' , 'comment_destroy/'.$cid  );
	}

	
	function call_method( $method , $action , $args = '' ) 
	{
		
		curl_setopt( $this->curl , CURLOPT_POSTFIELDS , join( '&' , $this->postdata ) );
		
		$url = $this->base . $method . '/' . $action . '.json' . $args ;
		curl_setopt($this->curl , CURLOPT_URL , $url );
		
		return json_decode(curl_exec( $this->curl ) , true);
		
	}
	
	function __destruct ()
	{
		curl_close($this->curl);
	}
	
	

	
	
	
	
	
	//function 
	
	
	

	
	
}


?>