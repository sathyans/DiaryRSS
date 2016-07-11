<?

 
class RSS
{
 public function RSS()
 {
  require_once ('includes/pdoconn.php');
 }
  
 public function GetFeed($conn)
 {
  return $this->getChannel($conn,1).$this->getItems($conn);
 }
  
 
 private function getDetails($conn)
 {
  $detailsTable = "rssDetails";
  $query = "SELECT * FROM ". $detailsTable." ORDER BY `id` DESC LIMIT 31";
  $result = $conn->prepare($query);
  $result->execute();

   
  foreach ($result as $row)
  {
   $details = '<?xml version="1.0" encoding="ISO-8859-1" ?>
    <rss version="2.0">
     <channel>
      <title>'. $row['title'] .'</title>
      <link>'. $row['link'] .'</link>
      <description>'. $row['description'] .'</description>
      <language>'. $row['language'] .'</language>
      <image>
       <title>'. $row['image_title'] .'</title>
       <url>'. $row['image_url'] .'</url>
       <link>'. $row['image_link'] .'</link>
       <width>'. $row['image_width'] .'</width>
       <height>'. $row['image_height'] .'</height>
      </image>';
  }
  return $details;
 }
  
 private function getChannel($conn,$channelNumber)
 {
  $channelTable = "rssChannel";
  $query = "SELECT * FROM ". $channelTable." WHERE id = ? LIMIT 31";
  $result = $conn->prepare($query);
  $result->bindValue(1,$channelNumber);
  $result->execute();

   
  foreach ($result as $row)
  {
   $channel = '<?xml version="1.0" encoding="ISO-8859-1" ?>
    <rss version="2.0">
     <channel>
      <title>'. $row['title'] .'</title>
      <link>'. $row['link'] .'</link>
      <description>'. $row['description'] .'</description>
    <language>'. $row['language'] .'</language>
      <pubDate>'.date(DATE_RSS).'</pubDate>';
  }
  return $channel;
 }
  
 private function getItems($conn)
 {
  $itemsTable = "rssItems";
  $query = "SELECT * FROM ". $itemsTable." ORDER BY `id` DESC  LIMIT 31";
  $result = $conn->prepare($query);
  $result->execute();
  $items = '';
  foreach($result as $row)
  {
   $items .= '<item>
    <title>'. $row["title"] .'</title>
    <link>'. $row["link"] .'</link>
    <description><![CDATA['. $row["description"] .']]></description>
   </item>';
  }
  $items .= '</channel>
    </rss>';
  return $items;
 }
 
}
header("Content-Type: application/xml; charset=ISO-8859-1");
  require_once ('includes/pdoconn.php');
$rss = new RSS();
echo $rss->GetFeed($pdoconn);
?>
 