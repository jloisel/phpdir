<?php
/**
* Rss Feed base class.
* @author PHPClasses.net
*/
class RssFeed
{

  public $rss_version = '2.0';
  public $encoding = '';

  public function createFeed($channel)
  {
    $rss = '<?xml version="1.0"';
    if (!empty($this->encoding))
    {
      $rss .= ' encoding="' . $this->encoding . '"';
    }
    $rss .= '?>' . "\n";
    $rss .= '<!-- Generated on ' . date('r') . ' -->' . "\n";
    $rss .= '<rss version="' . $this->rss_version . '">' . "\n";
    $rss .= '  <channel>' . "\n";
    $rss .= '    <title>' . $channel->title . '</title>' . "\n";
    $rss .= '    <link>' . $channel->link . '</link>' . "\n";
    $rss .= '    <description>' . $channel->description . '</description>' . "\n";
    if (!empty($channel->language))
    {
      $rss .= '    <language>' . $channel->language . '</language>' . "\n";
    }
    if (!empty($channel->copyright))
    {
      $rss .= '    <copyright>' . $channel->copyright . '</copyright>' . "\n";
    }
    if (!empty($channel->managingEditor))
    {
      $rss .= '    <managingEditor>' . $channel->managingEditor . '</managingEditor>' . "\n";
    }
    if (!empty($channel->webMaster))
    {
      $rss .= '    <webMaster>' . $channel->webMaster . '</webMaster>' . "\n";
    }
    if (!empty($channel->pubDate))
    {
      $rss .= '    <pubDate>' . $channel->pubDate . '</pubDate>' . "\n";
    }
    if (!empty($channel->lastBuildDate))
    {
      $rss .= '    <lastBuildDate>' . $channel->lastBuildDate . '</lastBuildDate>' . "\n";
    }
	if (!empty($channel->categories))
	{
	    foreach ($channel->categories as $category)
	    {
	      $rss .= '    <category';
	      if (!empty($category['domain']))
	      {
	        $rss .= ' domain="' . $category['domain'] . '"';
	      }
	      $rss .= '>' . $category['name'] . '</category>' . "\n";
	    }
	}
    if (!empty($channel->generator))
    {
      $rss .= '    <generator>' . $channel->generator . '</generator>' . "\n";
    }
    if (!empty($channel->docs))
    {
      $rss .= '    <docs>' . $channel->docs . '</docs>' . "\n";
    }
    if (!empty($channel->ttl))
    {
      $rss .= '    <ttl>' . $channel->ttl . '</ttl>' . "\n";
    }
    if (!empty($channel->skipHours))
    {
      $rss .= '    <skipHours>' . "\n";
      foreach ($channel->skipHours as $hour)
      {
        $rss .= '      <hour>' . $hour . '</hour>' . "\n";
      }
      $rss .= '    </skipHours>' . "\n";
    }
    if (!empty($channel->skipDays))
    {
      $rss .= '    <skipDays>' . "\n";
      foreach ($channel->skipDays as $day)
      {
        $rss .= '      <day>' . $day . '</day>' . "\n";
      }
      $rss .= '    </skipDays>' . "\n";
    }
    if (!empty($channel->image))
    {
      $image = $channel->image;
      $rss .= '    <image>' . "\n";
      $rss .= '      <url>' . $image->url . '</url>' . "\n";
      $rss .= '      <title>' . $image->title . '</title>' . "\n";
      $rss .= '      <link>' . $image->link . '</link>' . "\n";
      if (image.width)
      {
        $rss .= '      <width>' . $image->width . '</width>' . "\n";
      }
      if ($image.height)
      {
        $rss .= '      <height>' . $image->height . '</height>' . "\n";
      }
      if (!empty($image->description))
      {
        $rss .= '      <description>' . $image->description . '</description>' . "\n";
      }
      $rss .= '    </image>' . "\n";
    }
    if (!empty($channel->textInput))
    {
      $textInput = $channel->textInput;
      $rss .= '    <textInput>' . "\n";
      $rss .= '      <title>' . $textInput->title . '</title>' . "\n";
      $rss .= '      <description>' . $textInput->description . '</description>' . "\n";
      $rss .= '      <name>' . $textInput->name . '</name>' . "\n";
      $rss .= '      <link>' . $textInput->link . '</link>' . "\n";
      $rss .= '    </textInput>' . "\n";
    }
    if (!empty($channel->cloud_domain) || !empty($channel->cloud_path) ||
      !empty($channel->cloud_registerProcedure) || !empty($channel->cloud_protocol))
    {
      $rss .= '    <cloud domain="' . $channel->cloud_domain . '" ';
      $rss .= 'port="' . $channel->cloud_port . '" path="' . $channel->cloud_path . '" ';
      $rss .= 'registerProcedure="' . $channel->cloud_registerProcedure . '" ';
      $rss .= 'protocol="' . $channel->cloud_protocol . '" />' . "\n";
    }
    if (!empty($channel->extraXML))
    {
      $rss .= $channel->extraXML . "\n";
    }
    foreach ($channel->items as $item)
    {
      $rss .= '    <item>' . "\n";
      if (!empty($item->title))
      {
        $rss .= '      <title>' . $item->title . '</title>' . "\n";
      }
      if (!empty($item->description))
      {
        $rss .= '      <description>' . $item->description . '</description>' . "\n";
      }
      if (!empty($item->link))
      {
        $rss .= '      <link>' . $item->link . '</link>' . "\n";
      }
      if (!empty($item->pubDate))
      {
        $rss .= '      <pubDate>' . $item->pubDate . '</pubDate>' . "\n";
      }
      if (!empty($item->author))
      {
        $rss .= '      <author>' . $item->author . '</author>' . "\n";
      }
      if (!empty($item->comments))
      {
        $rss .= '      <comments>' . $item->comments . '</comments>' . "\n";
      }
      if (!empty($item->guid))
      {
        $rss .= '      <guid isPermaLink="';
        $rss .= ($item->guid_isPermaLink ? 'true' : 'false') . '">';
        $rss .= $item->guid . '</guid>' . "\n";
      }
      if (!empty($item->source))
      {
        $rss .= '      <source url="' . $item->source_url . '">';
        $rss .= $item->source . '</source>' . "\n";
      }
      if (!empty($item->enclosure_url) || !empty($item->enclosure_type))
      {
        $rss .= '      <enclosure url="' . $item->enclosure_url . '" ';
        $rss .= 'length="' . $item->enclosure_length . '" ';
        $rss .= 'type="' . $item->enclosure_type . '" />' . "\n";
		$rss .= '      <thumbnail>'.$item->enclosure_url.'</thumbnail>'."\n";
      }
      foreach ($item->categories as $category)
      {
        $rss .= '      <category';
        if (!empty($category['domain']))
        {
          $rss .= ' domain="' . $category['domain'] . '"';
        }
        $rss .= '>' . $category['name'] . '</category>' . "\n";
      }
      $rss .= '    </item>' . "\n";
    }
    $rss .= '  </channel>' . "\n";
    return $rss .= '</rss>';
  }

}

class RssFeedChannel
{

  public $title = '';
  public $link = '';
  public $description = '';
  public $language = '';
  public $copyright = '';
  public $managingEditor = '';
  public $webMaster = '';
  public $pubDate = '';
  public $lastBuildDate = '';
  public $categories = array();
  public $generator = '';
  public $docs = '';
  public $ttl = '';
  public $image = '';
  public $textInput = '';
  public $skipHours = array();
  public $skipDays = array();
  public $cloud_domain = '';
  public $cloud_port = '80';
  public $cloud_path = '';
  public $cloud_registerProcedure = '';
  public $cloud_protocol = '';
  public $items = array();
  public $extraXML = '';

}

class RssFeedImage
{

  public $url = '';
  public $title = '';
  public $link = '';
  public $width = '88';
  public $height = '31';
  public $description = '';

}

class RssFeedTextInput
{

  public $title = '';
  public $description = '';
  public $name = '';
  public $link = '';

}

class RssFeedItem
{

  public $title = '';
  public $description = '';
  public $link = '';
  public $author = '';
  public $pubDate = '';
  public $comments = '';
  public $guid = '';
  public $guid_isPermaLink = true;
  public $source = '';
  public $source_url = '';
  public $enclosure_url = '';
  public $enclosure_length = '0';
  public $enclosure_type = '';
  public $categories = array();

}

?>