<?php
/**
 * PHPMailer2.php
 *
 * PHP version 7.2 (and up)
 * @category   Email Transport
 * @package    PHPMailer2
 * @author     Andy Prevost <andy@codeworxtech.com>
 * @copyright  2004-2022 (C) Andy Prevost - All Rights Reserved
 * @version    1rc1
 * @license    MIT - Distributed under the MIT License, available at:
 *             http://www.opensource.org/licenses/mit-license.html
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
**/
/* Last updated on: 2022-05-01 18:48:13 (EST) */

namespace codeworxtech;

if (version_compare(PHP_VERSION, '7.2.0', '<=') ) { exit("Sorry, this version of PHPMailer2 will only run on PHP version 7.2 or greater!\n"); }

class PHPMailer2 {

  /* CONSTANTS */
  const ERR_CONTINUE = 1; // echo message, process ok to continue
  const ERR_CRITICAL = 9; // echo message, process die critical error
  const VERSION      = '1rc1';

  /* SMTP CONSTANTS */
  const EOL          = "\r\n";
  const TIMEVAL      = 30; // seconds
  const PASSMK       = '&#10003; '; //checkmark
  const FAILMK       = '&#10007; '; //X

  /* PROPERTIES, PRIVATE & PROTECTED */
  private   $all_recipients = [];
  private   $attachments    = [];
  private   $bcc            = [];
  private   $boundary       = [];
  private   $cc             = [];
  private   $CustomHeader   = [];
  private   $error_count    = 0;
  private   $exceptions     = true;
  protected $language       = [];
  private   $message_type   = "";
  private   $ReplyTo        = [];
  protected $sendEmailArray = [];
  private   $sign_cert_file = "";
  private   $sign_key_file  = "";
  private   $sign_key_pass  = "";
  private   $SMTP_fdbk      = [];
  private   $SMTP_Stream    = 0;
  private   $to             = [];

  /* PROPERTIES, PUBLIC */

  /**
   * Sets message CharSet
   * @var string
   */
  public $CharSet = 'UTF-8';

  /**
   * Sets email address that a "read" confirmation will be sent to.
   * @var string
   */
  public $ConfirmReadingTo = '';

  /**
   * Sets message Content-type
   * @var string
   */
  public $ContentType = 'text/plain';

  /**
   * Required: Used with DKIM DNS Resource Record
   * syntax: (base domain) 'yourdomain.com'
   * @var string
   */
  public $DKIM_domain = '';

  /**
   * Optional: Used with DKIM DNS Resource Record
   * syntax: 'you@yourdomain.com'
   * @var string
   */
  public $DKIM_identity = '';

  /**
   * Optional: Used with DKIM Digital Signing process
   * @var string
   */
  public $DKIM_passphrase = '';

  /**
   * Required: Used with DKIM DNS Resource Record
   * private key (read from /.htkey_private)
   * @var string
   */
  public $DKIM_private = '';

  /**
   * Used with DKIM DNS Resource Record
   * @var string
   */
  public $DKIM_selector = 'PHPMailer2';

  /**
   * Sets message Encoding. Options:
   *   "8bit", "7bit", "binary", "base64", and "quoted-printable"
   * @var string
   */
  public $Encoding = 'base64';

  /**
   * Most recent mailer error message.
   * @var string
   */
  public $ErrorInfo = '';

  /**
   * Sets the hostname to use in Message-Id and Received headers
   * and as default HELO string. If empty, the value returned
   * by SERVER_NAME is used or 'localhost.localdomain'.
   * @var string
   */
  public $Hostname = '';

  /**
   * Sets message Body. Can be HTML or text.
   * @var string
   */
  public $MessageHTML = '';

  /**
   * Sets iCalendar/ICS message.
   * @var string
   */
  public  $MessageICal = '';

  /**
   * Sets message ID to be used in the Message-Id header.
   * If empty, a unique id will be generated.
   * @var string
   */
  public $MessageID = '';

  /**
   * Sets text-only body. Automatically sets email to
   * multipart/alternative. This body can be read by mail clients that do not
   * have HTML email capability. Clients that read HTML will view the HTML Body
   * @var string
   */
  public $MessageText = ''; //'To view the message, please use an HTML compatible email viewer!\r\n';

  /**
   * Useful if SMTP2 class
   * is in a different directory than the PHP include path.
   * @var string
   */
  public $PluginDir = ''; //'_plugins';

  /**
   * Email priority (1 = High, 3 = Normal, 5 = low).
   * Not sent when set to 0 (default)
   * @var int
   */
  public $Priority = 0;

  /**
   * email Return-Path. If not empty, sent via -f or in headers
   * @var string
   */
  public $ReturnPath = [];

  /**
   * Sets message From email address
   * @var string
   */
  public $SenderEmail = 'root@localhost';

  /**
   * Sets message From name
   * @var string
   */
  public $SenderName = 'Root User';

  /**
   * Provides the ability to have the TO field process individual
   * emails, instead of sending to entire TO addresses
   * @var bool
   */
  public $SendIndividualEmails = true;

  /**
   * Default path of the sendmail program.
   * @var string
   */
  public $SendmailServerPath = '';

  /**
   * Sets message Subject
   * @var string
   */
  public $Subject = '';

  /**
   * Default method to send mail.
   * Options are 'sendmail', 'smtp'
   * NOTE: for Qmail, Postfix, Exim set as 'sendmail' (all include a sendmail wrapper for compatibility)
   * @var string
   */
  public $Transport = 'sendmail';

  /**
   * Sets word wrapping on the body of the message to a given number of
   * characters.
   * @var int
   */
  public $WordWrap = 70;

  /* SMTP PROPERTIES, PRIVATE & PROTECTED */
  /**
   * Contains SMTP account: username and password
   * @var array
   */
  public  $SMTP_Account   = [];

  /**
   * Debug level
   * @var int
   */
  public  $SMTP_Debug     = 0;

  /**
   * Account domain (top level domain)
   * @var string
   */
  public  $SMTP_Domain    = '';

  /**
   * SMTP Mail-From: (email address only)
   * @var string
   */
  public  $SMTP_From      = '';

  /**
   * SMTP Host (hostname, MX) example: mail.yourhost.com
   * @var string
   */
  public  $SMTP_Host      = '';

  /**
   * SMTP KeepAlive, triggers a reset if true to prevent the SMTP server from closing
   * @var boolean
   */
  public  $SMTP_KeepAlive = false;

  /**
   * Work around to authentication issues
   * @var array
   */
  public  $SMTP_Options   = []; // ['ssl'=>[ 'verify_peer'=>false, 'verify_peer_name'=>false, 'allow_self_signed'=>true ] ];

  /**
   * SMTP Account password
   * @var string
   */
  public  $SMTP_Password  = '';

  /**
   * SMTP Port (note, only support 25, 587 and 2525. Most hosting companies have deprecated 465)
   * @var int / string
   */
  public  $SMTP_Port      = '';

  /**
   * SMTP Account username
   * @var string
   */
  public  $SMTP_Username  = '';

  /**
   * VERP = Variable Envelope Return Path (used for bounce handling)
   * @var boolean
   */
  public  $SMTP_Useverp   = false;

  /**
   * Class Construct
   */
  public function __construct() {
    if (trim(ini_get('sendmail_path')) != '') {
      $this->SendmailServerPath = ini_get('sendmail_path');
    }
    // Get mail domain name for this server (mx record)
    $this->SMTP_Domain = self::GetMailServer();
    // Set the boundaries
    $this->boundary['wrap'] = md5(uniqid(time()+1) . uniqid()) . '_w1';
    $this->boundary['body'] = md5(uniqid(time()+2) . uniqid()) . '_b1';
  }

  /**
   * Class Call (checks for valid methods)
   */
  public function __call($name, $arguments) {
    if (!method_exists($this, $name)) {
      throw new Exception($name . ' is not a valid method.');
    }
  }

  /**
   * Class Destruct
   */
  public function __destruct() {
    self::Clear();
    if (self::SMTP_IsStreamConnected()) {
      self::SMTP_Quit();
    }
    if ($this->SMTP_Debug > 0 && count($this->SMTP_fdbk) > 0) {
      foreach ($this->SMTP_fdbk as $msg) {
        echo $msg;
      }
    }
  }

  /* METHODS */

  /**
   * Adds an address to one of the recipient arrays
   * Addresses that have been added already return false, but do not throw exceptions
   * @param string $kind One of 'to', 'cc', 'bcc', 'ReplyTo'
   * @param string $var1 (email address or name)
   * @param string $var2 (email address or name)
   * @return boolean true on success, false if address already used or invalid in some way
   */
  protected function AddAnAddress($kind, $var1='', $var2='') {
    if (!preg_match('/^(to|cc|bcc|ReplyTo)$/', $kind)) {
      self::SetError(self::Lang('invalid_address') .': '. $kind . '<br>' . self::EOL);
      return false;
    }
    $data  = self::ObjectToArray([$var1=>$var2]);
    $name  = reset($data);
    $email = key($data);
    if (!static::ValidateAddress($email)) {
      self::SetError(self::Lang('invalid_address').': '. $email . '<br>' . self::EOL);
      if ($this->exceptions) {
        throw new Exception(self::Lang('invalid_address').': '.$email . '<br>' . self::EOL);
      }
      return false;
    }
    if ($kind != 'ReplyTo') {
      if (!isset($this->all_recipients[strtolower($email)])) {
        if (!is_array($this->$kind)) { $this->$kind = []; }
        $this->$kind = $this->$kind + [$email=>$name];
        $this->all_recipients[strtolower($email)] = $name;
        return true;
      }
    } else {
      if (!array_key_exists(strtolower($email), $this->ReplyTo)) {
        $this->ReplyTo[strtolower($email)] = $name;
        return true;
      }
    }
    return false;
  }

  /**
   * Adds an attachment from a path on the filesystem.
   * Returns false if the file could not be found
   * or accessed.
   * @param string $path Path to the attachment.
   * @param string $name Overrides the attachment name.
   * @param string $encoding File encoding (see $Encoding).
   * @param string $type File extension (MIME) type.
   * @return bool
   */
  public function AddAttachment($path, $name='', $encoding='base64', $type='') {
    try {
      if (static::IsExploitPath($path, true)) {
        throw new Exception(self::Lang('execute') . '<br>' . self::EOL, PHPMailer2::ERR_CRITICAL);
      }
      if ( !@is_file($path) ) {
        throw new Exception(self::Lang('file_access') . $path . '<br>' . self::EOL, PHPMailer2::ERR_CONTINUE);
      }
      if ($type == '') { self::GetMimeType($path); }
      $filename = basename($path);
      if ($name == '') { $name = $filename; }
      $this->attachments[] = [ 0=>$path,1=>$filename,2=>$name,3=>$encoding,4=>$type,5=>false,6=>'attachment',7=>0 ];
    } catch (Exception $e) {
      self::SetError($e->getMessage());
      if ($this->exceptions) {
        throw $e;
      }
      echo $e->getMessage()."\n";
      if ( $e->getCode() == PHPMailer2::ERR_CRITICAL ) {
        return false;
      }
    }
    return true;
  }

  /**
   * Adds a "Bcc" email address.
   * Note: works with SMTP mailer on win32, not with 'mail' mailer
   * @param string $email
   * @param string $name
   * @return boolean true on success, false if address already used
   */
  public function AddBCC( $data ) {
    foreach ($data as $key=>$val) {
      $data  = self::ObjectToArray([$key=>$val]);
      $name  = reset($data);
      $email = key($data);
      self::AddAnAddress('bcc', $email, $name);
    }
  }

  /**
   * Adds a "Cc" address.
   * Note: works with SMTP mailer on win32, not with 'mail' mailer
   * @param string $email
   * @param string $name
   * @return boolean true on success, false if address already used
   */
  public function AddCC($data) {
    foreach ($data as $key=>$val) {
      $data  = self::ObjectToArray([$key=>$val] );
      $name  = reset($data);
      $email = key($data);
      self::AddAnAddress('cc', $email, $name);
    }
  }

  /**
   * Adds a custom header.
   * @return void
   */
  public function AddCustomHeader($custom_header) {
    $this->CustomHeader[] = explode(':', $custom_header, 2);
  }

  /**
   * Adds an embedded attachment.
   * @param string $path Path to the attachment.
   * @param string $cid Content ID of the attachment.
   * @param string $name Overrides the attachment name.
   * @param string $encoding File encoding (see $Encoding).
   * @param string $type File extension (MIME) type.
   * @return bool
   */
  public function AddEmbeddedImage($path, $cid, $name='', $encoding='base64', $type='') {
    if (static::IsExploitPath($path, true)) {
      throw new Exception(self::Lang('execute') . '<br>' . self::EOL, PHPMailer2::ERR_CRITICAL);
    }
    if ( !@is_file($path) ) {
      self::SetError(self::Lang('file_access') . $path . '<br>' . self::EOL);
      return false;
    }
    if ($type == '') { self::GetMimeType($path); }
    $filename = basename($path);
    if ($name == '') { $name = $filename; }
    // Append to $attachments array
    $this->attachments[] = [ 0=>$path,1=>$filename,2=>$name,3=>$encoding,4=>$type,5=>false,6=>'inline',7=>$cid ];
    return true;
  }

  /**
   * Creates recipient headers.
   * @return string
   */
  public function AddrAppend($type, $addr) {
    $addr_str  = $type . ': ';
    $addresses = [];
    if (count($addr) > 1) {
      foreach ($addr as $key => $val) {
        $addresses[] = self::AddrFormatRFC2822([$key=>$val]);
      }
    } else {
      $addresses[] = self::AddrFormatRFC2822($addr);
    }
    $addr_str .= implode(', ', $addresses);
    $addr_str .= self::EOL;
    return $addr_str;
  }

  /**
   * Adds a "To" address.
   * @param string $address
   * @param string $name
   * @return boolean true on success, false if address already used
   */
  public function AddRecipient($data) {
    $data  = self::ObjectToArray($data);
    $name  = reset($data);
    $email = key($data);
    return self::AddAnAddress('to', $email, $name);
  }

  /**
   * Adds a "Reply-to" address.
   * @param string $email
   * @param string $name
   * @return boolean
   */
  public function AddReplyTo($data) {
    $data  = self::ObjectToArray($data);
    $name  = reset($data);
    $email = key($data);
    return self::AddAnAddress('ReplyTo', $email, $name);
  }

  /**
   * Structures email address/name as defined in RFC 2822
   * https://www.rfc-editor.org/rfc/rfc2822
   * @param array (or string - detect)
   * @return string
   */
  private function AddrFormatRFC2822($param,$raw=false) {
    $param = self::ObjectToArray($param);
    $addr_str = '';
    foreach ($param as $var1 => $var2) {
      $data  = [ $var1 => $var2 ];
      $name  = reset($data);
      $email = key($data);
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        exit('Bad Email: "' . $email . '"');
      }
      if (trim($name) != '' && $raw === false) {
        $addr_str .= '"' . self::MS_Mb_Encode($name) . '" <' . $email . '>, ';
      } elseif (trim($name) != '' && $raw !== false) {
        $addr_str .= '"' . $name . '" <' . $email . '>, ';
      } else {
        $addr_str .= '<'.$email.'>, ';
      }
    }
    return rtrim($addr_str, ', ');
  }

  /**
   * Adds a string or binary attachment (non-filesystem) to the list.
   * This method can be used to attach ascii or binary data,
   * such as a BLOB record from a database.
   * @param string $string String attachment data.
   * @param string $filename Name of the attachment.
   * @param string $encoding File encoding (see $Encoding).
   * @param string $type File extension (MIME) type.
   * @return void
   */
  public function AddStringAttachment($string, $filename, $encoding='base64', $type='') {
    if (static::IsExploitPath($string, true)) {
      throw new Exception(self::Lang('execute') . '<br>' . self::EOL, PHPMailer2::ERR_CRITICAL);
    }
    if ($type == '') { self::GetMimeType($string,'string'); }
    $this->attachments[] = [ 0=>$string,1=>$filename,2=>basename($filename),3=>$encoding,4=>$type,5=>true,6=>'attachment',7=>0 ];
  }

  /**
   * Clear all
   */
  public function Clear() {
    unset($this->cc);
    unset($this->bcc);
    unset($this->all_recipients);
    unset($this->attachments);
    unset($this->MessageHTML);
    unset($this->MessageText);
  }

  /**
   * Clears all recipients assigned in the TO array. Returns void.
   * @return void
   */
  public function ClearAddresses() {
    foreach($this->to as $to) {
      unset($this->all_recipients[strtolower($to[0])]);
    }
    $this->to = [];
  }

  /**
   * Clears all recipients assigned in the TO, CC and BCC
   * array. Returns void.
   * @return void
   */
  public function ClearAllRecipients() {
    $this->to  = [];
    $this->cc  = [];
    $this->bcc = [];
    $this->all_recipients = [];
  }

  /**
   * Clears all previously set filesystem, string, and binary
   * attachments. Returns void.
   * @return void
   */
  public function ClearAttachments() {
    $this->attachments = [];
  }

  /**
   * Clears all recipients assigned in the BCC array. Returns void.
   * @return void
   */
  public function ClearBCCs() {
    foreach($this->bcc as $bcc) {
      unset($this->all_recipients[strtolower($bcc[0])]);
    }
    $this->bcc = [];
  }

  /**
   * Clears all recipients assigned in the CC array. Returns void.
   * @return void
   */
  public function ClearCCs() {
    foreach($this->cc as $cc) {
      unset($this->all_recipients[strtolower($cc[0])]);
    }
    $this->cc = [];
  }

  /**
   * Clears all custom headers. Returns void.
   * @return void
   */
  public function ClearCustomHeaders() {
    $this->CustomHeader = [];
  }

  /**
   * Clears all recipients assigned in the ReplyTo array. Returns void.
   * @return void
   */
  public function ClearReplyTos() {
    $this->ReplyTo = [];
  }

  /**
   * Build attachment
   * @param string $attachment
   * @return string
   */
  private function BuildAttachment($attachment='',$bkey='wrap') {
    $mime = $cidUniq = $incl = [];
    // add parameter passed in function
    if ($attachment != '' && is_string($attachment)) {
      if (self::IsPathSafe($attachment) !== true) { return false; }
      $mimeType = (function_exists('mime_content_type')) ? mime_content_type($attachment) : 'application/octet-stream';
      $fileContent = file_get_contents($attachment);
      $fileContent = chunk_split(base64_encode($fileContent));
      $data  = 'Content-Type: ' . $mimeType . '; name=' . basename($attachment) . self::EOL;
      $data .= 'Content-Transfer-Encoding: ' . $this->encode_hdr . self::EOL;
      $data .= 'Content-ID: <' . basename($attachment) . '>' . self::EOL;
      $data .= self::EOL . $fileContent . self::EOL . self::EOL;
      $data  = self::GetBoundary($bkey) . $data;
      $this->tot_attach[] = $attachment;
      return $data . self::EOL . self::EOL;
    }
    // Add all other attachments and check for string attachment
    $bString = $attachment[5];
    if ($bString) {
      $string = $attachment[0];
    } else {
      $path = $attachment[0];
      if (self::IsPathSafe($path) !== true) { return false; }
    }

    if (in_array($attachment[0], $incl)) { return; }
    if (in_array($path, $incl)) { return; }

    $filename    = $attachment[1];
    $name        = $attachment[2];
    $encoding    = $attachment[3];
    $type        = $attachment[4];
    $disposition = $attachment[6];
    $cid         = $attachment[7];
    $incl[]      = $attachment[0];

    if ( $disposition == 'inline' && isset($cidUniq[$cid]) ) { return; }
    $cidUniq[$cid] = true;

    $mime[] = 'Content-Type: ' . $type . '; name="' . $name . '"' . self::EOL;
    $mime[] = 'Content-Transfer-Encoding: ' . $encoding . self::EOL;

    if ($disposition == 'inline') {
      $mime[] = 'Content-ID: <'.$cid.'>' . self::EOL;
    }
    $mime[] = 'Content-Disposition: ' . $disposition . '; filename="' . $name . '"' . self::EOL . self::EOL;

    // Encode as string attachment
    if ($bString) {
      $mime[] = chunk_split(base64_encode($string), $this->WordWrap, self::EOL);
      $mime[] = self::EOL . self::EOL;
    } else {
      $mime[] = chunk_split(base64_encode( file_get_contents($path) ), $this->WordWrap, self::EOL);
      $mime[] = self::EOL . self::EOL;
    }
    $data = implode('', $mime);
    $data = self::GetBoundary($bkey) . $data;
    return $data . self::EOL . self::EOL;;
  }

  private function BuildBody() {
    static::IsExploitPath($this->MessageHTML);
    static::IsExploitPath($this->MessageICal);
    self::getMsgType();
    if (is_file($this->MessageHTML)) {
      $thisdir = (dirname($this->MessageHTML) != '') ? rtrim(dirname($this->MessageHTML),'/') . '/' : '';
      self::Data2HTML(file_get_contents($this->MessageHTML),$thisdir);
      self::getMsgType();
    }
    if (is_file($this->MessageICal)) {
      $this->MessageICal = file_get_contents($this->MessageICal);
    }

    $gBEnd = '';
    $body  = '';
    $body .= self::EOL;
    $body .= 'This is a multipart message in MIME format.' . self::EOL;
    $body .= self::EOL;
    // wrapper
    if ($this->message_type != 'message' && $this->message_type != 'ics') {
      $gBEnd = self::GetBoundary('wrap','--');
      $body .= self::GetBoundary('wrap');
      if ($this->message_type == 'attachment_inline_message') {
        $body .= self::GetContentTypeHdr('multipart/related','body','hdr') . self::EOL;
      } else {
        $body .= self::GetContentTypeHdr('multipart/alternative','body','hdr') . self::EOL;
      }
      $body .= self::EOL;
    }
    // inline only
    if ($this->message_type == 'inline') {
      $body .= self::GetBoundary('body');
      $body .= self::GetContentTypeBody('text/plain','charset="us-ascii"','7bit') . self::EOL;
      $body .= self::EOL;
      $body .= self::GetBoundary('body');
      $body .= self::EOL;
      foreach ($this->attachments as $attachment) {
        if ($attachment[6] === 'inline') {
          $body .= self::GetBoundary('wrap');
          $body .= self::GetContentTypeBody($attachment[4],'name="'.$attachment[1].'"','base64',$attachment[7]) . self::EOL;
        }
      }
      $body .= self::GetBoundary('wrap');
    }
    // attachment only
    elseif ($this->message_type == 'attachment') {
      $body .= self::GetBoundary('body');
      $body .= self::GetContentTypeBody('text/plain','charset="us-ascii"','7bit') . self::EOL;
      $body .= self::EOL;
      $body .= self::GetBoundary('body','--');
      $body .= self::EOL;
      foreach ($this->attachments as $attachment) {
        if ($attachment[6] === 'attachment') {
          $body .= self::BuildAttachment($attachment,'wrap');
        }
      }
    }
    // message only
    elseif ($this->message_type == 'message') {
      $body .= self::GetMsgPart('wrap');
      if (!empty(trim($this->MessageICal))) { $body .= self::EOL; $body .= self::GetIcsPart('wrap'); }
      $body .= self::EOL;
      $body .= self::GetBoundary('wrap','--');
    }
    // ics only
    elseif ($this->message_type == 'ics') {
      if (!empty(trim($this->MessageICal))) {
        $body .= self::GetIcsPart('none');
      }
    }
    // message with inline (iCalendar option)
    elseif ($this->message_type == 'inline_message' || $this->message_type == 'ics_inline_message') {
      $body .= self::GetMsgPart('body');
      if (!empty(trim($this->MessageICal))) { $body .= self::EOL; $body .= self::GetIcsPart('body'); }
      $body .= self::EOL;
      $body .= self::GetBoundary('body','--');
      $body .= self::EOL;
      // inline
      foreach ($this->attachments as $attachment) {
        if ($attachment[6] === 'inline') {
          $body .= self::BuildAttachment($attachment,'wrap');
        }
      }
    }
    // message with attachment (iCalendar option)
    elseif ($this->message_type == 'attachment_message' || $this->message_type == 'attachment_ics_message') {
      $body .= self::GetMsgPart('body');
      if (!empty(trim($this->MessageICal))) { $body .= self::EOL; $body .= self::GetIcsPart('body'); }
      $body .= self::EOL;
      $body .= self::GetBoundary('body','--');
      $body .= self::EOL;
      // attachment
      foreach ($this->attachments as $attachment) {
        if ($attachment[6] === 'attachment') {
          $body .= self::BuildAttachment($attachment,'wrap');
        }
      }
    }
    // message with attachment
    elseif ($this->message_type == 'attachment_inline_message') {
      $this->boundary['spec'] = md5(uniqid(time()+3) . uniqid()) . '_b2';
      $body .= self::GetBoundary('body');
      $body .= self::GetContentTypeHdr('multipart/alternative','spec','hdr') . self::EOL;
      $body .= self::EOL;
      $body .= self::GetMsgPart('spec');
      if (!empty(trim($this->MessageICal))) { $body .= self::EOL; $body .= self::GetIcsPart('spec'); }
      $body .= self::EOL;
      $body .= self::GetBoundary('spec','--');
      $body .= self::EOL;
      // inline
      $endInlineBoundary = '';
      foreach ($this->attachments as $attachment) {
        if ($attachment[6] === 'inline') {
          $body .= self::BuildAttachment($attachment,'body');
          $endInlineBoundary = self::GetBoundary('body','--');
        }
      }
      $body .= $endInlineBoundary;
      $body .= self::EOL;
      // attachment
      $endAttachBoundary = '';
      foreach ($this->attachments as $attachment) {
        if ($attachment[6] === 'attachment') {
          $body .= self::BuildAttachment($attachment,'wrap');
          $endAttachBoundary = self::GetBoundary('wrap','--');
        }
      }
      $body .= $endAttachBoundary;
      $body .= self::EOL;
    }
    // message with inline, attachment and ics
    elseif ($this->message_type == 'attachment_ics_inline_message') {
      $body .= self::GetMsgPart('body');
      $body .= self::EOL;
      $body .= self::GetBoundary('body');
      // iCal
      if (!empty(trim($this->MessageICal))) {
        $allowed_methods = ['ADD','CANCEL','COUNTER','DECLINECOUNTER','PUBLISH','REFRESH','REPLY','REQUEST'];
        $method = "";
        $lines = explode("\n",$this->MessageICal);
        foreach ($lines as $line) {
          if (strpos($line,'METHOD:') !== false) {
            $line = str_replace(["\r",' '],'',$line);
            $bits = explode(':',$line);
            $method = strtoupper($bits[1]);
          }
        }
        if ($method != "" && in_array($method, $allowed_methods)) {
          $body .= self::GetBoundary('body');
          $body .= 'Content-Type: text/calendar; method='.$method . '; charset="' . $this->CharSet . '";' . self::EOL;
          $body .= 'Content-Transfer-Encoding: 7bit' . self::EOL;
          $body .= self::EOL;
          $body .= wordwrap($this->MessageICal, $this->WordWrap) . self::EOL;
          $body .= self::EOL;
          $body .= self::GetBoundary('body');
        }
      }
      // attachment
      foreach ($this->attachments as $attachment) {
        if ($attachment[6] === 'attachment') {
          $body .= self::BuildAttachment($attachment,'wrap');
        }
      }
    }
    $body .= $gBEnd;
    return $body;
  }

  /**
   * Assembles message header.
   * @return string The assembled header
   */
  public function BuildHeader() {
    $hdr = 'X-Mailer: PHPMailer2 v' . PHPMailer2::VERSION . ' ' . $this->Transport . ' (phpmailer2.com)' . self::EOL;
    if ($this->Priority !== 0) {
      $hdr .= 'X-Priority: ' . $this->Priority . self::EOL;
    }
    $hdr .= 'X-Originating-IP: '.$_SERVER['SERVER_ADDR'] . self::EOL;
    $hdr .= 'Date: ' . date('r O') . self::EOL;
    if ($this->MessageID != '') {
      $hdr .= 'Message-Id: <' . $this->MessageID . '>' . self::EOL;
    } else {
      $hdr .= 'Message-Id: <' . md5((idate("U")-1000000000).uniqid()).'@' . self::ServerHostname() . '>' . self::EOL;
    }
    $hdr .= 'From: <' . $this->SenderEmail . '>' . self::EOL;
    if (count($this->ReplyTo) > 0) {
      $hdr .= 'Reply-to: ' . self::AddrFormatRFC2822($this->ReplyTo) . self::EOL;
    }
    if ($this->ReturnPath == '') {
      $hdr .= 'Return-Path: ' . self::AddrFormatRFC2822([$this->SenderEmail=>$this->SenderName]) . self::EOL;
    } else {
      $hdr .= 'Return-Path: ' . self::AddrFormatRFC2822($this->ReturnPath) . self::EOL;
    }
    if ($this->ConfirmReadingTo != '') {
      $hdr .= 'X-Confirm-Reading-To: ' . self::AddrFormatRFC2822($this->ConfirmReadingTo) . self::EOL;
      $hdr .= 'Disposition-Notification-To: ' . $this->confirm_read . self::EOL;
      $hdr .= 'Return-receipt-to: ' . $this->confirm_read . self::EOL;
    }
    if ($this->SendIndividualEmails === true) {
      if (count($this->to) > 1) {
        foreach($this->to as $t) {
          $this->sendEmailArray[] = self::AddrFormatRFC2822($t);
        }
      } else {
      $this->sendEmailArray[] = self::AddrFormatRFC2822($this->to);
      }
    } else {
      if (count($this->to) == 0 && count($this->bcc) > 0) {
        $hdr .= 'To: undisclosed-recipients:;' . self::EOL;
      }
    }
    if ($this->Transport == 'smtp' && count($this->to) > 0) {
      $hdr .= 'To: ' . self::AddrFormatRFC2822($this->to) . self::EOL;
    }

    if ($this->Transport != 'smtp' && count($this->bcc) > 0) {
      $sendbcc = '';
      foreach ($this->bcc as $email => $name) {
        $sendbcc .= self::AddrFormatRFC2822([$email=>$name]) . ', ';
      }
      $hdr .= 'Bcc: ' . rtrim($sendbcc,', ') . '' . self::EOL;
    }
    if (count($this->cc) > 0) {
      $sendcc = self::AddrFormatRFC2822($this->cc) . ',';
      $hdr .= 'Cc: ' . rtrim($sendcc,',') . self::EOL;
    }
    $hdr .= 'Subject: ' . self::MS_Mb_Encode($this->Subject) . self::EOL;

    // Add custom headers
    for($index = 0; $index < count($this->CustomHeader); $index++) {
      $hdr .= trim($this->CustomHeader[$index][0]) . ': ' . $this->MS_Mb_Encode(trim($this->CustomHeader[$index][1])) . self::EOL;
    }
    if (!$this->sign_key_file) {
      $hdr .= 'MIME-Version: 1.0' . self::EOL;
      if ($this->message_type == 'message') {
        $hdr .= self::GetContentTypeHdr('multipart/alternative','wrap','hdr') . self::EOL;
      } elseif ($this->message_type == 'inline' || $this->message_type == 'message_inline') {
        $hdr .= self::GetContentTypeHdr('multipart/related','wrap','hdr') . self::EOL;
      } elseif ($this->message_type == 'ics') {
        $hdr .= self::GetIcsPart('wrap','hdr');
      } else {
        $hdr .= self::GetContentTypeHdr('multipart/mixed','wrap','hdr') . self::EOL;
      }
    }
    return $hdr;
  }

  /**
   * Create the DKIM header, body, as new header
   * @param string $headers_line
   * @param string $subject
   * @param string $body
   */
  public function DKIM_Add($headers_line,$subject,$body) {
    $DKIMsignatureType    = 'rsa-sha256';
    $DKIMcanonicalization = 'relaxed/simple';
    $DKIMquery            = 'dns/txt';
    $DKIMtime             = time() ;
    $subject_header       = "Subject: $subject";
    $headers              = explode(self::EOL,$headers_line);
    foreach($headers as $header) {
      if (strpos($header,'From:') === 0) {
        $from_header = $header;
      } elseif (strpos($header,'To:') === 0) {
        $to_header = $header;
      }
    }
    $from     = str_replace('|','=7C',$this->DKIM_QP($from_header));
    $to       = str_replace('|','=7C',$this->DKIM_QP($to_header));
    $subject  = str_replace('|','=7C',$this->DKIM_QP($subject_header));
    $body     = $this->DKIM_BodyC($body);
    $DKIMlen  = strlen($body);
    $DKIMb64  = base64_encode(pack("H*", hash('sha256', $body)));
    $ident    = ($this->DKIM_identity == '')? '' : " i=" . $this->DKIM_identity . ";";
    $dkimhdrs = "DKIM-Signature: v=1;" .
      " a=" . $DKIMsignatureType . ";" .
      " q=" . $DKIMquery . ";" .
      " l=" . $DKIMlen . ";" .
      " s=" . $this->DKIM_selector . ";" . self::EOL.
      " t=" . $DKIMtime . ";" .
      " c=" . $DKIMcanonicalization . ";" . self::EOL.
      " h=From:To:Subject;" . self::EOL.
      " d=" . $this->DKIM_domain . ";" . $ident . self::EOL.
      " z=$from" . self::EOL.
      " |$to" . self::EOL.
      " |$subject;" . self::EOL.
      " bh=" . $DKIMb64 . ";" . self::EOL.
      " b=";
    $toSign   = $this->DKIM_HeaderC($from_header . self::EOL . $to_header . self::EOL . $subject_header . self::EOL . $dkimhdrs);
    $signed   = $this->DKIM_Sign($toSign) . self::EOL;
    return $dkimhdrs.$signed;
  }

  /**
   * Generate DKIM Body
   * @param string $body
   */
  public function DKIM_BodyC($body) {
    if ($body == '') { return self::EOL; }
    $body = self::FixEOL($body);
    return str_replace(self::EOL . self::EOL,self::EOL,$body);
  }

  /**
   * Generate DKIM Header
   * @param string $s Header
   */
  public function DKIM_HeaderC($header) {
    $header = self::FixEOL($header);
    $header = preg_replace("/\r\n\s+/"," ",$header);
    $lines  = explode(self::EOL,$header);
    foreach ($lines as $key=>$line) {
      list($heading,$value) = explode(":", $line,2);
      $heading     = strtolower($heading);
      $value       = preg_replace("/\s+/"," ",$value);
      $lines[$key] = $heading.":".trim($value);
    }
    return implode(self::EOL,$lines);
  }

  /**
   * Set the private key file and password to sign the message.
   * @param string $key_filename Parameter File Name
   * @param string $key_pass Password for private key
   */
  public function DKIM_QP($txt) {
    $tmp = $line = "";
    for ($i=0;$i<strlen($txt);$i++) {
      $ord = ord($txt[$i]);
      if ( ((0x21 <= $ord) && ($ord <= 0x3A)) || $ord == 0x3C || ((0x3E <= $ord) && ($ord <= 0x7E)) ) {
        $line .= $txt[$i];
      } else {
        $line .= "=" . sprintf("%02X",$ord);
      }
    }
    return $line;
  }

  /**
   * Generate DKIM signature
   * @param string $s Header
   */
  public function DKIM_Sign($s) {
    if (static::IsExploitPath($this->DKIM_private, true)) {
      throw new Exception(self::Lang('execute') . '<br>' . self::EOL, PHPMailer2::ERR_CRITICAL);
    }
    $privKeyStr = file_get_contents($this->DKIM_private);
    if ($this->DKIM_passphrase!='') {
      $privKey = openssl_pkey_get_private($privKeyStr,$this->DKIM_passphrase);
    } else {
      $privKey = $privKeyStr;
    }
    if (openssl_sign($s, $signature, $privKey)) {
      return base64_encode($signature);
    }
  }

  /**
   * Changes every end of line from CR or LF to CRLF. then to preferred EOL
   * @return string
   */
  private function FixEOL($str) {
    return str_replace(["\r\n", "\r", "\n"], self::EOL, $str);
  }

  /**
   * Return the current array of attachments
   * @return array
   */
  public function GetAttachments() {
    return $this->attachments;
  }

  /**
   * Creates the boundary line / end boundary line
   * @param string $type = wrap, body, spec, none
   * @param string $end (optional, triggers adding two dashes at end)
   * @return string (boundary line)
   */
  private function GetBoundary($type,$end='') {
    return '--' . $this->boundary[$type] . $end . self::EOL;
  }

  /**
   * Creates the Content-Type directive for the body
   * @param string $type = multipart/mixed / multipart/related / multipart/alternative
   * @param string $charset
   * @param string $encoding
   * @param string $cid (optional)
   * @return string (content type line)
   */
  private function GetContentTypeBody($type,$charset,$encoding,$cid='') {
    $data  = 'Content-Type: ' . $type . ';' . self::EOL;;
    $data . "\t" . $charset . self::EOL;
    $data .= 'Content-Transfer-Encoding: ' . $encoding . self::EOL;
    if ($cid != '') {
      $data .= 'Content-ID: <' . $cid . '>' . self::EOL;
    }
    return $data;
  }

  /**
   * Creates the Content-Type directive for the header
   * type = multipart/mixed / multipart/related / multipart/alternative
   * bkey = boundary (wrap / body / spec)
   * @return string (content type line)
   */
  private function GetContentTypeHdr($type,$bkey,$what='') {
    if ($what=='hdr') {
      $data = "Content-Type: " . $type . ";" . "\n";
      return $data . "\t" . 'boundary="' . $this->boundary[$bkey] . '"';
    }
    $data = "Content-Type: " . $type . ";" . "\n";
    return $data . "\t" . 'boundary="' . $this->boundary[$bkey] . '"';
  }

  /**
   * Builds ICS/iCalendar portion of message
   * @return string
   */
  private function GetIcsPart($boundary,$hdr='') {
    if (!empty(trim($this->MessageICal))) {
      $data = '';
      $allowed_methods = ['ADD','CANCEL','COUNTER','DECLINECOUNTER','PUBLISH','REFRESH','REPLY','REQUEST'];
      $method = "";
      $lines = explode("\n",$this->MessageICal);
      foreach ($lines as $line) {
        if (strpos($line,'METHOD:') !== false) {
          $line = str_replace(["\r",' '],'',$line);
          $bits = explode(':',$line);
          $method = strtoupper($bits[1]);
        }
      }
      if ($method != "" && in_array($method, $allowed_methods)) {
        $dhdr  = 'Content-Type: text/calendar; method='.$method . '; charset="' . $this->CharSet . '";' . self::EOL;
        $dhdr .= 'Content-Transfer-Encoding: 7bit' . self::EOL;
        if ($hdr == '') {
          if ($boundary != 'none') {
            $data .= '--'.$this->boundary[$boundary] . self::EOL;
            $data .= $dhdr;
          }
          $data .= self::EOL;
          $data .= wordwrap($this->MessageICal, $this->WordWrap) . self::EOL;
          $data .= self::EOL;
          return $data;
        }
        return $dhdr;
      }
    }
    return;
  }

  /**
   * Gets MIME type of file or string
   * if file: USE ONLY AFTER VERIFYING FILE EXISTS
   * if string: designed for file data read in as string, will not properly detect html vs text
   * returns 'application/octet-stream' if not found (or file encoded)
   * @param string $resource (filename or string)
   * @param string $type     ('string' or 'file', defaults to 'file')
   * @return string
   */
  public static function GetMimeType($resource,$type='file') {
    if ($type == 'string') {
      if (function_exists('finfo_buffer') && function_exists('finfo_open') && defined('FILEINFO_MIME_TYPE')) {
        return finfo_buffer(finfo_open(FILEINFO_MIME_TYPE),$resource);
      }
    } else {
      if (function_exists('finfo_file') && function_exists('finfo_open') && defined('FILEINFO_MIME_TYPE')) {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE),$resource);
      }
      if (function_exists('mime_content_type')) {
        return mime_content_type($resource);
      }
    }
    return 'application/octet-stream';
  }

  /**
   * Builds plain text and HTML portion of message
   * @return string
   */
  private function GetMsgPart($bkey) {
    $data  = '';
    $data .= self::GetBoundary($bkey);
    $data .= self::GetContentTypeBody('text/plain','charset="' . $this->CharSet . '"','7bit');
    $data .= self::EOL;
    $wrapText = '';
    if (trim($this->MessageText) != '') {
      $wrapText = wordwrap($this->MessageText, $this->WordWrap);
    }
    $data .= $wrapText . self::EOL;
    if (trim($this->MessageHTML) != '') {
      $data .= self::GetBoundary($bkey);
      $data .= self::GetContentTypeBody('text/html','charset="' . $this->CharSet . '"','base64') . self::EOL;
      $data .= self::EOL;
      $data .= base64_encode($this->MessageHTML) . self::EOL;
    }
    return $data;
  }

  /**
   * Gets email message type
   * @return string
   */
  private function getMsgType($type='') {
    if (is_string($type) && $type != '') {
      $type = rtrim($type,'_') . '_';
      $type = explode('_',$type);
    } else {
      $type = [];
    }
    if (!in_array('message',$type) && ($this->MessageHTML != '' || $this->MessageText != '')) {
      $type[] = 'message';
    }
    foreach ($this->attachments as $attachment) {
      if ($attachment[6] === 'inline') {
        if (!in_array('inline',$type)) {
          $type[] = 'inline';
        }
      }
    }
    foreach ($this->attachments as $attachment) {
      if ($attachment[6] === 'attachment') {
        if (!in_array('attachment',$type)) {
          $type[] = 'attachment';
        }
      }
    }
    if (!in_array('ics',$type) && $this->MessageICal != '') {
      $type[] = 'ics';
    }
    sort($type);
    $this->message_type = implode('_',$type);
  }

  /**
   * Returns true if an error occurred.
   * @return bool
   */
  public function IsError() {
    return ($this->error_count > 0);
  }

  /**
   * Check file path for possible exploits and vulnerabilities.
   * - exploits: LFI/File manipulation, Directory traversal, File disclosure, Encoding, RCE
   * @param string $path Relative or absolute path to a file
   * @return bool
   */
  protected static function IsExploitPath($path, $opt_exit=false) {
    $na_protocol = ['data:','file:','glob:','phar:','php:','zip:'];
    foreach ($na_protocol as $type) {
      if (stripos($path, $type) !== false) {
        if ($opt_exit) { throw new Exception(self::Lang('execute') . '<br>' . self::EOL, PHPMailer2::ERR_CRITICAL); }
        return true;
      }
    }
    // possible exploit outside file path
    if (strpos($path, '..') !== false) {
      if ($opt_exit) { throw new Exception(self::Lang('execute') . '<br>' . self::EOL, PHPMailer2::ERR_CRITICAL); }
      return true;
    }
    return false;
  }

  /**
   * Checks string for multibyte characters
   * @param $str string
   * @return boolean (true if multibyte)
   */
  private function Is_Multibyte($str) {
    return (mb_strlen($str) != strlen($str)) ? true : false;
  }

  /**
   * Check if file path is safe (real, accessible).
   * @param string $path Relative or absolute path to a file
   * @return bool
   */
  protected static function IsPathSafe($path) {
    if (static::IsExploitPath($path, true)) { return false; }
    if (is_file($path)) { $path = str_replace(basename($path),'',$path); }
    $realPath = str_replace(rtrim($_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['PHP_SELF']),'/').'/','',realpath($path));
    if (strpos($path,'/')) { $realPath = rtrim($realPath,'/').'/'; }
    if (($path === false) || (strcmp($path, $realPath) !== 0)) { return false; }
    return (file_exists($path) && is_readable($path) && is_dir($path)) ? true: false;
  }

  /**
   * Prevent attacks by disallowing unsafe shell characters.
   * Modified version (Thanks to Paul Buonopane <paul@namepros.com>)
   * @param  string  $string (the string to be tested for shell safety)
   * @return bool
   */
  protected static function IsShellSafe($str) {
    if ( (empty(trim($str))) ||
         (escapeshellcmd($str) !== $str) ||
         (!in_array(escapeshellarg($str), ["'{$str}'","\"{$str}\""])) ||
         (preg_match('/[^a-zA-Z0-9@_\\-.]/', $str) !== 0)
       ) { return false; }
    return true;
  }

  /**
   * Sets Mailer to send message using SMTP.
   * Deprecated, will be removed in a future release
   * @return void
   */
  public function IsSMTP() {
    $this->Transport = 'smtp';
  }

  /**
   * Sets Mailer to send message using SMTP.
   * Replaces IsSMTP()
   * @return void
   */
  public function useSMTP() {
    $this->Transport = 'smtp';
  }

  /**
   * Returns a message in the appropriate language.
   * @return string
   */
  protected function Lang($key) {
    if (count($this->language) < 1) {
      self::SetLanguage();
    }
    return (isset($this->language[$key])) ? $this->language[$key] : 'Language string failed to load: ' . $key;
  }

  /**
   * Evaluates the message and returns modifications for inline images and backgrounds
   * @return $message
   */
  public function Data2HTML($content, $basedir = '') {
    if (static::IsExploitPath($content, true)) {
      throw new Exception(self::Lang('execute') . '<br>' . self::EOL, PHPMailer2::ERR_CRITICAL);
    }
    if (is_file($content)) {
      $thisdir = (dirname($content) != '') ? rtrim(dirname($content),'/') . '/' : '';
      $basedir = ($basedir == '') ? $thisdir : '';
      $content = file_get_contents($content);
    }
    preg_match_all("/(src|background)=\"(.*)\"/Ui", $content, $images);
    if (isset($images[2])) {
      foreach($images[2] as $i => $url) {
        if (!preg_match('#^[A-z]+://#',$url)) {
          if ($basedir != '') { $url = rtrim($basedir,'/') . '/' . $url; }
          $filename  = basename($url);
          $directory = dirname($url);
          $cid       = 'cid:' . md5($filename);
          if ($directory == '.') { $directory = ''; }
          if (function_exists('mime_content_type')) { $mimeType = mime_content_type($url); } else { $mimeType = 'application/octet-stream'; }
          if ( strlen($directory) > 1 && substr($directory,-1) != '/') { $directory .= '/'; }
          static::IsExploitPath($directory.$filename);
          static::IsExploitPath($url);
          if ( self::AddEmbeddedImage($directory.$filename, md5($filename), $filename, 'base64',$mimeType) ) {
            $content = preg_replace("/".$images[1][$i]."=\"".preg_quote($images[2][$i], '/')."\"/Ui", $images[1][$i]."=\"".$cid."\"", $content);
          }
        }
      }
    }
    self::SetTypeHTML();
    $this->MessageHTML = $content;
    $textMsg = trim(strip_tags(preg_replace('/<(head|title|style|script)[^>]*>.*?<\/\\1>/s','',$content)));
    if (!empty($textMsg) && empty($this->MessageText)) {
      $this->MessageText = html_entity_decode($textMsg,ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401,$this->CharSet);
    }
  }

  /**
   * Evaluates the message and returns modifications for inline images and backgrounds
   * @return $message
   */
  public function Data2ICSbody() {
    $data = $method = "";
    $allowed_methods = ['ADD','CANCEL','COUNTER','DECLINECOUNTER','PUBLISH','REFRESH','REPLY','REQUEST'];
    $lines  = explode("\n",$this->MessageICal);
    foreach ($lines as $line) {
      if (strpos($line,'METHOD:') !== false) {
        $line = str_replace(["\r",' '],'',$line);
        $bits = explode(':',$line);
        $method = strtoupper($bits[1]);
      }
    }
    if ($method != "" && in_array($method, $allowed_methods)) {
      $data .= '--'.$this->boundary['body'] . self::EOL;
      $data .= 'Content-Type: text/calendar; method='.$method . '; charset="' . $this->CharSet . '";' . self::EOL;
      $data .= 'Content-Transfer-Encoding: 7bit' . self::EOL;
      $data .= self::EOL;
      $data .= wordwrap($this->MessageICal, 70) . self::EOL;
      $data .= self::EOL;
    }
    return $data;
  }

  /**
   * Encodes and wraps long multibyte strings for mail headers
   * without breaking lines within a character.
   * Will validate $str as multibyte
   * @param string $str multi-byte string to encode
   * @return string
   */
  function MS_Mb_Encode($str,$len=75) {
    if (!self::Is_Multibyte($str)) { return $str; }
    $cwrx = 'aj';
    $nlen = $len + strlen($cwrx) + 2;
    return str_replace($cwrx . ': ','',str_replace("\n ","\n",iconv_mime_encode($cwrx,self::SafeStr($str),["line-length"=>$nlen])));
  }

  /**
   * Filter data (ascii and url-encoded) to prevent header injection
   * @param string $str String
   * @return string (trimmed)
   */
  public function SafeStr($str) {
    return trim(str_ireplace(["\r","\n","%0d","%0a","Content-Type:","bcc:","to:","cc:"],"",$str));
  }

  /**
   * Creates message and assigns Mailer. If the message is
   * not sent successfully then it returns false. Use the ErrorInfo
   * variable to view description of the error.
   * @return bool
   */
  public function Send() {
    if ( $this->Transport != 'smtp' && $this->SMTP_Host != '' ) {
      $this->Transport = 'smtp';
    }
    try {
      if ((count($this->to) + count($this->cc) + count($this->bcc)) < 1) {
        throw new Exception(self::Lang('provide_address') . '<br>' . self::EOL, PHPMailer2::ERR_CRITICAL);
      }
      if (!empty($this->MessageHTML)) {
        if (preg_match('/<\s?[^\>]*\/?\s?>/i',$this->MessageHTML) && $this->ContentType != 'text/html') {
          $this->ContentType = 'text/html';
          self::SetTypeHTML();
        }
      }
      if (!empty($this->MessageText)) {
        if (preg_match('/<\s?[^\>]*\/?\s?>/i',$this->MessageText) && $this->ContentType != 'text/html') {
          $this->MessageText = strip_tags($this->MessageText);
        }
        $this->ContentType = 'multipart/alternative';
      }
      $this->error_count = 0;
      $body = $this->BuildBody();
      $hdr  = $this->BuildHeader();
      if ($this->DKIM_domain && $this->DKIM_private) {
        $header_dkim = $this->DKIM_Add($hdr,$this->Subject,$body);
        $hdr = self::FixEOL($header_dkim) . $hdr;
      }
      $retSend = false;
      if ($this->Transport == 'smtp') {
        if ($this->SMTP_Username == '' && count($this->SMTP_Account) > 1) {
          $this->SMTP_Username = $this->SMTP_Account[0];
          $this->SMTP_Password = $this->SMTP_Account[1];
        }
        $retSend = self::TransportBySMTP($hdr,$body);
        if ($retSend === false && $this->SMTP_Username != '' && $this->SMTP_Password != '') {
          return false;
        } elseif ($retSend === true) {
          return true;
        }
      }
      if ($this->Transport == 'sendmail' || $retSend === false) {
        if (strpos('sendmail', $hdr) === false) {$hdr = str_replace(' (phpmailer2.com)','/sendmail (phpmailer2.com)',$hdr);}
        $retSend = self::TransportBySendmail($hdr,$body);
        if ($retSend === true) { return true; }
      }
      if (strpos('phpmail', $hdr) === false) {$hdr = str_replace(' (phpmailer2.com)','/phpmail (phpmailer2.com)',$hdr);}
      return self::TransportByPhpMail($hdr,$body);
    } catch (Exception $e) {
      self::SetError($e->getMessage());
      if ($this->exceptions) {
        throw $e;
      }
      echo $e->getMessage()."\n";
      return false;
    }
  }

  /**
   * Returns the server hostname or 'localhost.localdomain' if unknown.
   * @return string
   */
  private function ServerHostname() {
    if (!empty($this->Hostname)) {
      return $this->Hostname;
    } elseif (isset($_SERVER['SERVER_NAME'])) {
      return $_SERVER['SERVER_NAME'];
    }
    return 'localhost.localdomain';
  }

  /**
   * Set/Reset Class Objects (variables)
   * Usage Example:
   * $mail->set('X-Priority', '3');
   * @param string $name Parameter Name
   * @param mixed $value Parameter Value
   */
  public function Set($name, $value = '') {
    if ($name == '' && $value == '') {return true;}
    try {
      if (isset($this->$name) ) {
        $this->$name = $value;
      } else {
        throw new Exception(self::Lang('variable_set') . $name . '<br>' . self::EOL, PHPMailer2::ERR_CRITICAL);
      }
    } catch (Exception $e) {
      self::SetError($e->getMessage());
      if ($e->getCode() == PHPMailer2::ERR_CRITICAL) {
        return false;
      }
    }
    return true;
  }

  /**
   * Adds the error message to the error container.
   * @return void
   */
  protected function SetError($msg) {
    $this->error_count++;
    $this->ErrorInfo = $msg;
  }

  /**
   * Sets the language for all class error messages.
   * The default language is English 'en'
   * Based on ISO 639-1 2-character language code (ie. English: 'en')
   *       https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
   * @param string $langcode
   * @param string $lang_path Path to the language file directory
   * array named same as PHPMailer for compatibility
   */
  public function SetLanguage($langcode = 'en', $lang_path = 'language/') {
    $lang_arr = [
      'authenticate'        => 'SMTP Error: Could not authenticate.',
      'authenticated'       => 'Authentication initiated',
      'connect_host'        => 'SMTP Error: Could not connect to SMTP host.',
      'connected'           => 'Connection established',
      'connection_error'    => 'SMTP connection error, aborting',
      'connection_replies'  => 'Connection &amp; replies verified',
      'data_not_accepted'   => 'SMTP Error: Data not accepted.',
      'email_send'          => 'Unable to send e-mail, error: ',
      'empty_message'       => 'Message body empty',
      'encoding'            => 'Unknown encoding: ',
      'execute'             => 'Could not execute: ',
      'file_access'         => 'Could not access file: ',
      'file_open'           => 'File Error: Could not open file: ',
      'from_failed'         => 'The following From address failed: ',
      'instantiate'         => 'Could not instantiate mail function.',
      'invalid_address'     => 'Invalid address',
      'invalid_protocol'    => 'Invalid SMTP Protocol or SMTP Port',
      'keep_alive_accepted' => 'Keep Alive (RSET) sent and accepted',
      'keep_alive_error'    => 'Called Keep Alive without connection.',
      'MAIL_FROM'           => 'MAIL FROM sent and accepted',
      'not_connected'       => 'Critical error: not connected to SMTP server.',
      'password_accepted'   => 'Password accepted',
      'provide_address'     => 'You must provide at least one recipient email address.',
      'RCPT_TO'             => 'RCPT TO sent and accepted',
      'recipients_failed'   => 'SMTP Error: The following recipients failed: ',
      'server_response'     => 'Error while fetching server response.',
      'signing'             => 'Signing Error: ',
      'smtp_connect_failed' => 'SMTP connect() failed.',
      'STARTTLS'            => 'STARTTLS initiated',
      'transfer_accepted'   => 'Message transfer accepted',
      'transfer_completed'  => 'Email transfer completed and connection closed',
      'transfer_started'    => 'Data transfer initiated',
      'tls'                 => 'SMTP secure error: invalid tls or ssl',
      'unknown'             => 'unknown: ',
      'username_accepted'   => 'Username accepted',
      'variable_set'        => 'Cannot set or reset variable: '
    ];
    // optional use of PHPMailer language files
    if (static::IsExploitPath($lang_path.'phpmailer.lang-'.$langcode.'.php', true)) {
      throw new Exception(self::Lang('execute') . '<br>' . self::EOL, PHPMailer2::ERR_CRITICAL);
    }
    if ($langcode != 'en' && file_exists($lang_path.'phpmailer.lang-'.$langcode.'.php')) {
      @include($lang_path.'phpmailer.lang-'.$langcode.'.php');
      $lang_arr = $PHPMAILER_LANG;
    }
    $this->language = $lang_arr;
    return true;
  }

  /**
   * Set the SenderEmail and SenderName properties
   * @param string $email
   * @param string $name
   * @return boolean
   */
  public function SetSender($data) {
    $data  = self::ObjectToArray($data);
    $name  = @reset($data);
    $email = @key($data);
    if (!static::ValidateAddress($email)) {
      self::SetError(self::Lang('invalid_address').': '. $email . '<br>' . self::EOL);
      if ($this->exceptions) {
        throw new Exception(self::Lang('invalid_address').': '.$email . '<br>' . self::EOL);
      }
      echo self::Lang('invalid_address').': '.$email;
      return false;
    }
    $this->SenderEmail = $email;
    $this->SenderName  = $name;
    if (!self::IsShellSafe($this->SenderEmail)) {
      throw new Exception(self::Lang('invalid_address') . '<br>' . self::EOL, PHPMailer2::ERR_CRITICAL);
    }
    if (empty($this->ReplyTo)) {
      $this->ReplyTo[strtolower($email)] = $name;
    }
    if (empty($this->ReturnPath)) {
      $this->ReturnPath[strtolower($email)] = $name;
    }
    return true;
  }

  /**
   * Sets SMTP Username and password.
   * @return mixed
   */
  public function SetSMTPAccount($array) {
    $password = trim(reset($array));
    $username = (is_numeric(key($array))) ? $password : trim(key($array));
    if (trim($password) == '' || trim($username) == '') {
      throw new Exception(self::Lang('authenticate').'<br>' . self::EOL, PHPMailer2::ERR_CRITICAL);
    }
    $this->SMTP_Username = $username;
    $this->SMTP_Password = $password;
  }

  /**
   * Sets message type to HTML.
   * @param bool
   * @return void
   */
  public function SetTypeHTML($is_type_html = true) {
    $this->ContentType = ($is_type_html) ? 'text/html' : 'text/plain';
  }

  /**
   * Set the body wrapping.
   * @return void
   */
  public function SetWordWrap() {
    if ($this->WordWrap < 1) {
      return;
    }
    $this->MessageText = self::WrapText($this->MessageText, $this->WordWrap);
  }

  /**
   * Set the private key file and password to sign the message.
   * @param string $key_filename Parameter File Name
   * @param string $key_pass Password for private key
   */
  public function Sign($cert_filename, $key_filename, $key_pass) {
    $this->sign_cert_file = $cert_filename;
    $this->sign_key_file  = $key_filename;
    $this->sign_key_pass  = $key_pass;
  }

  /**
   * Returns a properly structured email/name array
   * Orders as Email first, Name second (name could be blank)
   * @return array
   */
  private function ObjectToArray($param,$var2='') {
    // OLD STYLE two-var conversion
    if ($var2 != '') {
      $param = [ $param=>$var2];
      return $param;
    }
    // string - convert to assoc array
    elseif (is_string($param)) {
      if (strpos($param,',') !== false) {
        // string with multiple email addresses (separated by comma)
        $param = explode(',',$param);
      } else {
        // single email address (string)
        $param = [$param=>''];
      }
    }
    // indexed array
    elseif (is_array($param) && array_keys($param) === range(0, count($param) - 1)) {
      $new_array = [];
      foreach ($param as $key=>$value) {
        if (is_array($value)) {
          foreach ($value as $subkey=>$subval) {
            if (is_numeric($subkey)) {
              $new_array[$subval] = '';
            } else {
              $new_array[$subkey] = $subval;
            }
          }
        } else {
          $new_array = $new_array + self::ObjectToArray($value);
        }
      }
      $param = $new_array;
    }
    // associative array
    else {
      $new_array = [];
      foreach ($param as $key=>$value) {
        if (!is_numeric($key)) {
          $new_array[$key] = $value;
        } else {
          $new_array = $new_array + self::ObjectToArray($value);
        }
      }
      $param = $new_array;
    }
    $new_array = [];
    foreach ($param as $key=>$val) {
      $new_array = $new_array + [$key=>$val];
    }
    return $param;
  }

  /**
   * Sends using the PHP mail() function
   * @param string $hdr The message headers
   * @param string $body The message body
   * @return bool
   */
  protected function transportByPhpMail($hdr, $body) {
    $toArr = [];
    foreach($this->to as $t) {
      $toArr[] = self::AddrFormatRFC2822($t);
    }
    $to = implode(', ', $toArr);
    $params = sprintf("-oi -f %s", $this->ReturnPath);
    if ($this->ReturnPath != '' && strlen(ini_get('safe_mode')) < 1) {
      $org_from = ini_get('sendmail_from');
      ini_set('sendmail_from', $this->ReturnPath);
      if ($this->SendIndividualEmails === true && count($toArr) > 1) {
        foreach ($toArr as $key=>$val) {
          $rt = @mail($val, $this->MS_Mb_Encode(self::SafeStr($this->Subject)), $body, $hdr, $params);
        }
      } else {
        $rt = @mail($to, $this->MS_Mb_Encode(self::SafeStr($this->Subject)), $body, $hdr, $params);
      }
    } else {
      if ($this->SendIndividualEmails === true && count($toArr) > 1) {
        foreach ($toArr as $key=>$val) {
          $rt = @mail($val, $this->MS_Mb_Encode(self::SafeStr($this->Subject)), $body, $hdr, $params);
        }
      } else {
        $rt = @mail($to, $this->MS_Mb_Encode(self::SafeStr($this->Subject)), $body, $hdr);
      }
    }
    if (isset($org_from)) {
      ini_set('sendmail_from', $org_from);
    }
    if (!$rt) {
      throw new Exception(self::Lang('instantiate') . '<br>' . self::EOL, PHPMailer2::ERR_CRITICAL);
    }
    return true;
  }

  /**
   * Sends mail using the Sendmail program.
   * @param string $hdr The message headers
   * @param string $body The message body
   * @return bool
   */
  protected function TransportBySendmail($hdr, $body) {
    // if any of the header email addresses fail IsShellSafe, treat as malicious and stop
    if ( !self::IsShellSafe($this->SenderEmail) ||
         (!empty($this->ReplyTo) && !self::IsShellSafe($this->ReplyTo)) ||
         (!empty($this->ReturnPath) && !self::IsShellSafe($this->ReturnPath))
       ) {
      throw new Exception(self::Lang('invalid_address') . '<br>' . self::EOL, PHPMailer2::ERR_CRITICAL);
    }
    if ($this->ReturnPath != '') {
      $sendmail = sprintf("%s -oi -f %s -t", escapeshellcmd($this->SendmailServerPath), $this->SenderEmail);
    } else {
      $sendmail = sprintf("%s -oi -t", escapeshellcmd($this->SendmailServerPath));
    }
    if ($this->SendIndividualEmails === true) {
      foreach ($this->sendEmailArray as $key=>$val) {
        if (!@$mail = popen($sendmail, 'w')) {
          throw new Exception(self::Lang('execute') . $this->SendmailServerPath . '<br>' . self::EOL, PHPMailer2::ERR_CRITICAL);
        }
        fputs($mail, "To: " . $val . "\n");
        fputs($mail, $hdr);
        fputs($mail, $body);
        $result = pclose($mail);
        if ($result != 0) {
          throw new Exception(self::Lang('execute') . $this->SendmailServerPath . '<br>' . self::EOL, PHPMailer2::ERR_CRITICAL);
        }
      }
    } else {
      if (!@$mail = popen($sendmail, 'w')) {
        throw new Exception(self::Lang('execute') . $this->SendmailServerPath . '<br>' . self::EOL, PHPMailer2::ERR_CRITICAL);
      }
      fputs($mail, $hdr);
      fputs($mail, $body);
      $result = pclose($mail);
      if ($result != 0) {
        throw new Exception(self::Lang('execute') . $this->SendmailServerPath . '<br>' . self::EOL, PHPMailer2::ERR_CRITICAL);
      }
    }
    return true;
  }

  /**
   * Sends mail via SMTP
   * exit() if there is a bad MAIL FROM, RCPT, or DATA input.
   * @param string $hdr  Email headers
   * @param string $body Email message
   * @return bool
   */
  protected function TransportBySMTP($hdr, $body) {
    // if any of the header email addresses fail IsShellSafe, treat as malicious and exit
    if ( !self::IsShellSafe($this->SenderEmail) ||
         (!empty($this->ReplyTo) && !self::IsShellSafe($this->ReplyTo)) ||
         (!empty($this->ReturnPath) && !self::IsShellSafe($this->ReturnPath))
       ) {
      throw new Exception(self::Lang('smtp_connect_failed') . '<br>' . self::EOL, PHPMailer2::ERR_CRITICAL);
    }
    $to_emails = [];
    if (count($this->to) > 0) {
      foreach ($this->to as $email => $name) {
        $to_emails = $to_emails + [$email=>$name];
      }
    }
    if (count($this->cc) > 0) {
      foreach ($this->cc as $email => $name) {
        $to_emails = $to_emails + [$email=>$name];
      }
    }
    if (count($this->bcc) > 0) {
      foreach ($this->bcc as $email => $name) {
        $to_emails = $to_emails + [$email=>$name];
      }
    }
    if (self::SMTP_Connect() === false) {
      $this->Transport = 'sendmail';
      return false;
    }
    self::SMTP_Recipient($to_emails);
    self::SMTP_Data($hdr,$body);
    if ($this->SMTP_KeepAlive == true) {
      self::SMTP_Reset();
    }
    return true;
  }

  /**
   * Finds last character boundary prior to maxLength in a utf-8 quoted
   * (printable) encoded string.
   * Original written by Colin Brown.
   * @param string $encodedText utf-8 QP text
   * @param int    $maxLength   find last character boundary prior to this length
   * @return int
   */
  public function UTF8CharBoundary($encodedText, $maxLength) {
    $foundSplitPos = false;
    $lookBack = 3;
    while (!$foundSplitPos) {
      $lastChunk = substr($encodedText, $maxLength - $lookBack, $lookBack);
      $encodedCharPos = strpos($lastChunk, "=");
      if ($encodedCharPos !== false) {
        $hex = substr($encodedText, $maxLength - $lookBack + $encodedCharPos + 1, 2);
        $dec = hexdec($hex);
        if ($dec < 128) { // Single byte character.
          $maxLength = ($encodedCharPos == 0) ? $maxLength :
          $maxLength - ($lookBack - $encodedCharPos);
          $foundSplitPos = true;
        } elseif ($dec >= 192) { // First byte of a multi byte character
          $maxLength = $maxLength - ($lookBack - $encodedCharPos);
          $foundSplitPos = true;
        } elseif ($dec < 192) { // Middle byte of a multi byte character, look further back
          $lookBack += 3;
        }
      } else {
        $foundSplitPos = true;
      }
    }
    return $maxLength;
  }

  /**
   * Validate an email address
   * @param string $email The email address to check
   * @return boolean
   */
  public static function ValidateAddress($email) {
    return (filter_var($email,FILTER_VALIDATE_EMAIL) === false) ? false : true;
  }

  /**
   * Wraps message for use with mailers that do not automatically perform
   * wrapping and for quoted-printable.
   * @param string $message The message to wrap
   * @param integer $length The line length to wrap to
   * @param boolean $qp_mode Whether to run in Quoted-Printable mode
   * @return string
   */
  public function WrapText($message, $length, $qp_mode = false) {
    $soft_break = ($qp_mode) ? sprintf(" =%s", self::EOL) : self::EOL;
    $is_utf8 = (strtolower($this->CharSet) == "utf-8");
    $message = self::FixEOL($message);
    if (substr($message, -1) == self::EOL) {
      $message = substr($message, 0, -1);
    }
    $line = explode(self::EOL, $message);
    $message = '';
    for ($i=0 ;$i < count($line); $i++) {
      $line_part = explode(' ', $line[$i]);
      $buf = '';
      for ($e = 0; $e<count($line_part); $e++) {
        $word = $line_part[$e];
        if ($qp_mode and (strlen($word) > $length)) {
          $space_left = $length - strlen($buf) - 1;
          if ($e != 0) {
            if ($space_left > 20) {
              $len = $space_left;
              if ($is_utf8) {
                $len = self::UTF8CharBoundary($word, $len);
              } elseif (substr($word, $len - 1, 1) == "=") {
                $len--;
              } elseif (substr($word, $len - 2, 1) == "=") {
                $len -= 2;
              }
              $part = substr($word, 0, $len);
              $word = substr($word, $len);
              $buf .= ' ' . $part;
              $message .= $buf . sprintf("=%s", self::EOL);
            } else {
              $message .= $buf . $soft_break;
            }
            $buf = '';
          }
          while (strlen($word) > 0) {
            $len = $length;
            if ($is_utf8) {
              $len = self::UTF8CharBoundary($word, $len);
            } elseif (substr($word, $len - 1, 1) == "=") {
              $len--;
            } elseif (substr($word, $len - 2, 1) == "=") {
              $len -= 2;
            }
            $part = substr($word, 0, $len);
            $word = substr($word, $len);

            if (strlen($word) > 0) {
              $message .= $part . sprintf("=%s", self::EOL);
            } else {
              $buf = $part;
            }
          }
        } else {
          $buf_o = $buf;
          $buf .= ($e == 0) ? $word : (' ' . $word);

          if (strlen($buf) > $length and $buf_o != '') {
            $message .= $buf_o . $soft_break;
            $buf = $word;
          }
        }
      }
      $message .= $buf . self::EOL;
    }
    return $message;
  }

  /* SMTP METHODS ************/

  /**
   * Connect to the server
   * return code: 220 success
   * @return bool
   */
  public function SMTP_Connect() {
    // check if already connected
    if ($this->SMTP_Stream) {
      return false;
    }
    // check for host
    if (isset($this->SMTP_Host) && $this->SMTP_Host != '') {
      $host_name  = $this->SMTP_Host;
      $server_arr = [$this->SMTP_Host];
    } else {
      $host_name  = $this->SMTP_Domain;
      $server_arr = [$this->SMTP_Domain];
    }
    // check for port
    if (isset($this->SMTP_Port) && $this->SMTP_Port != '') {
      $srv_ports  = [$this->SMTP_Port];
    } else {
      $srv_ports  = [587,25,2525];
    }
    // connect to the smtp server
    $connect_options = $this->SMTP_Options;
    $create_options  = (!empty($connect_options)) ? stream_context_create($connect_options) : null;
    foreach ($server_arr as $host) {
      if (!isset($code) || $code != '220') {
        foreach ($srv_ports as $port) {
          if (function_exists('stream_socket_client')) {
            $this->SMTP_Stream = @stream_socket_client($host.':'.$port, $errno, $errstr, PHPMailer2::TIMEVAL, STREAM_CLIENT_CONNECT, $create_options);
          } else {
            $this->SMTP_Stream = @fsockopen($host,$port,$errno,$errstr, PHPMailer2::TIMEVAL);
          }
          if (!$this->SMTP_Stream) { return false; }
          $code = self::SMTP_GetResponse(['220'], 'CONNECT (' . $host.':'.$port.')');
          if ($code == '220') { $this->SMTP_Host = $host; break; }
        }
      } else {
        break;
      }
    }
    // set the time out
    stream_set_timeout($this->SMTP_Stream, PHPMailer2::TIMEVAL);
    // send EHLO command
    fwrite($this->SMTP_Stream, 'EHLO ' . $this->SMTP_Host . self::EOL);
    self::SMTP_GetResponse(['250'], 'EHLO');
    if (!self::SMTP_IsStreamConnected()) {
      exit(__LINE__ . ' ' . PHPMailer2::FAILMK . self::Lang('not_connected') . '<br>' . self::EOL);
    }

    // send STARTTLS command
    fwrite($this->SMTP_Stream, 'STARTTLS' . self::EOL);
    $test = self::SMTP_GetResponse(['220'], 'STARTTLS');

    // initiate secure tls encryption
    $crypto_method = STREAM_CRYPTO_METHOD_TLS_CLIENT;
        if (defined('STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT')) { $crypto_method = STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT; }
    elseif (defined('STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT')) { $crypto_method = STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT; }
    elseif (defined('STREAM_CRYPTO_METHOD_TLSv1_0_CLIENT')) { $crypto_method = STREAM_CRYPTO_METHOD_TLSv1_0_CLIENT; }
    stream_socket_enable_crypto($this->SMTP_Stream, true, $crypto_method);

    // resend EHLO after tls negotiation
    fwrite($this->SMTP_Stream, 'EHLO ' . $this->SMTP_Host . self::EOL);
    self::SMTP_GetResponse(['250'], 'EHLO');

    if (!self::SMTP_IsStreamConnected()) {
      exit(__LINE__ . ' ' . PHPMailer2::FAILMK . self::Lang('not_connected') . '<br>' . self::EOL);
    }
    if ( (isset($this->SMTP_Username) && $this->SMTP_Username != '') && (isset($this->SMTP_Username) && $this->SMTP_Username != '') ) {
      // Authenticate
      fwrite($this->SMTP_Stream,'AUTH LOGIN' . self::EOL);
      self::SMTP_GetResponse(['334'], 'AUTH LOGIN');
      // Send encoded username
      fwrite($this->SMTP_Stream, base64_encode($this->SMTP_Username) . self::EOL);
      self::SMTP_GetResponse(['334'], 'USER');
      // Send encoded password
      fputs($this->SMTP_Stream, base64_encode($this->SMTP_Password) . self::EOL);
      self::SMTP_GetResponse(['235'], 'PASS');
    }
    if (!self::SMTP_IsStreamConnected()) {
      exit(__LINE__ . ' ' . PHPMailer2::FAILMK . self::Lang('not_connected') . '<br>' . self::EOL);
    }
    // send MAIL FROM command
    fwrite($this->SMTP_Stream,"MAIL FROM: <" . $this->SMTP_From . ">" . (($this->SMTP_Useverp) ? "XVERP" : "") . self::EOL);
    self::SMTP_GetResponse(['250'], 'MAIL FROM');
    if (!self::SMTP_IsStreamConnected()) {
      exit(__LINE__ . ' ' . PHPMailer2::FAILMK . self::Lang('not_connected') . '<br>' . self::EOL);
    }
    return true;
  }

  /**
   * Sends header and message to SMTP Server
   * return code: 250 success (possible 251, have to allow for this)
   * @return bool
   */
  public function SMTP_Data($hdr,$msg) {
    if (!self::SMTP_IsStreamConnected()) {
      exit(__LINE__ . ' ' . PHPMailer2::FAILMK . self::Lang('not_connected') . '<br>' . self::EOL);
    }
    // initiate DATA stream
    fwrite($this->SMTP_Stream,"DATA" . self::EOL);
    self::SMTP_GetResponse(['354'], 'DATA');
    // send the header
    fwrite($this->SMTP_Stream, $hdr . self::EOL);
    // send the message
    fwrite($this->SMTP_Stream, $msg . self::EOL);
    // end DATA stream
    fwrite($this->SMTP_Stream,'.' . self::EOL);
    self::SMTP_GetResponse(['250'], 'END');
    return true;
  }

  /**
   * Get response code returned by SMTP server
   * @return string
   */
  private function SMTP_GetResponse($expected_code, $command='') {
    $line = '';
    $data = '';
    $cmd  = ($command != '') ? '' . $command . ' - ' : '';
    while (substr($line, 3, 1) != ' ') {
      $line = stream_get_line($this->SMTP_Stream, 2048, "\n");
      $data  .= $line;
      if (!$line) {
        exit(PHPMailer2::FAILMK . $cmd . ' ' . self::Lang('server_response') . '<br>' . self::EOL);
      }
    }
    if (!in_array(substr($line, 0, 3), $expected_code)) {
      exit(PHPMailer2::FAILMK . $cmd . ' ' . self::Lang('email_send') . ' "'.$line.'"<br>' . self::EOL);
    }
    if ($this->SMTP_Debug > 0) {
      $code     = substr($line, 0, 3);
      $thisCode = substr($data, 0, 4);
      $data = str_replace($thisCode,' | ',$data);
      $data = str_replace($code.' ','',$data);
      $data = ltrim($data,' | ');
      $data = $thisCode . $data;
      $debug_text = ($this->SMTP_Debug > 0) ? ' (' . $data . ')' : '';
      // put any response into feedback array
      switch ($command) {
        case strstr($command,'CONNECT'):
          $this->SMTP_fdbk[] = PHPMailer2::PASSMK . self::Lang('connected') . $debug_text . '<br>' . self::EOL;
          break;
        case strstr($command,'AUTH'):
          $this->SMTP_fdbk[] = PHPMailer2::PASSMK . self::Lang('authenticated') . $debug_text . '<br>' . self::EOL;
          break;
        case 'DATA':
          $this->SMTP_fdbk[] = PHPMailer2::PASSMK . self::Lang('transfer_started') . $debug_text . '<br>' . self::EOL;
          break;
        case 'EHLO':
          $this->SMTP_fdbk[] = PHPMailer2::PASSMK . self::Lang('connection_replies') . $debug_text . '<br>' . self::EOL;
          break;
        case 'END':
          $this->SMTP_fdbk[] = PHPMailer2::PASSMK . self::Lang('transfer_accepted') . $debug_text . '<br>' . self::EOL;
          break;
        case 'HELO':
          $this->SMTP_fdbk[] = PHPMailer2::PASSMK . self::Lang('connection_replies') . $debug_text . '<br>' . self::EOL;
          break;
        case 'MAIL FROM':
          $this->SMTP_fdbk[] = PHPMailer2::PASSMK . self::Lang('MAIL_FROM') . $debug_text . '<br>' . self::EOL;
          break;
        case 'PASS':
          $this->SMTP_fdbk[] = PHPMailer2::PASSMK . self::Lang('password_accepted') . $debug_text . '<br>' . self::EOL;
          break;
        case 'QUIT':
          $this->SMTP_fdbk[] = PHPMailer2::PASSMK . self::Lang('transfer_completed') . $debug_text . '<br>' . self::EOL;
          break;
        case 'RCPT TO':
          $this->SMTP_fdbk[] = PHPMailer2::PASSMK . self::Lang('RCPT_TO') . $debug_text . '<br>' . self::EOL;
          break;
        case 'RSET':
          $this->SMTP_fdbk[] = PHPMailer2::PASSMK . self::Lang('keep_alive_accepted') . $debug_text . '<br>' . self::EOL;
          break;
        case 'STARTTLS':
          $this->SMTP_fdbk[] = PHPMailer2::PASSMK . self::Lang('STARTTLS') . $debug_text . '<br>' . self::EOL;
          break;
        case 'USER':
          $this->SMTP_fdbk[] = PHPMailer2::PASSMK . self::Lang('username_accepted') . $debug_text . '<br>' . self::EOL;
          break;
        default:
          $this->SMTP_fdbk[] = self::Lang('unknown') . $command . $debug_text . '<br>' . self::EOL;
      }
    }
    return substr($line, 0, 3);
  }

  /**
   * Returns true if connected to a server otherwise false
   * @access public
   * @return bool
   */
  public function SMTP_IsStreamConnected() {
    if (!empty($this->SMTP_Stream)) {
      $status = socket_get_status($this->SMTP_Stream);
      if ($status["eof"]) {
        fclose($this->SMTP_Stream);
        $this->SMTP_Stream = 0;
        exit(PHPMailer2::FAILMK . self::Lang('connection_error') . '<br>' . self::EOL);
        return false;
      }
      return true;
    }
    return false;
  }

  /**
   * Sends QUIT to SMTP Server then closes the stream
   * return code: 221 success
   * @return bool
   */
  public function SMTP_Quit() {
    if (!self::SMTP_IsStreamConnected()) {
      exit(__LINE__ . ' ' . PHPMailer2::FAILMK . self::Lang('not_connected') . '<br>' . self::EOL);
    }
    // send QUIT to the SMTP server
    fwrite($this->SMTP_Stream,"quit" . self::EOL);
    self::SMTP_GetResponse(['221'], 'QUIT');
    // close connection and reset SMTP_Stream
    if (!empty($this->SMTP_Stream)) {
      fclose($this->SMTP_Stream);
      $this->SMTP_Stream = 0;
    }
    return true;
  }

  /**
   * Sends smtp command RCPT TO
   * Returns true if recipient (email) accepted (false if not accepted).
   * return code: 250 success (possible 251, have to allow for this)
   * @return bool
   */
  public function SMTP_Recipient($param) {
    if (is_string($param) && strpos($param, ',') !== false) {
      $emails = explode(',',$param);
    } elseif (is_string($param)) {
      fwrite($this->SMTP_Stream,"RCPT TO: <" . trim($param) . ">" . self::EOL);
      $code = self::SMTP_GetResponse(['250','251'], 'RCPT TO');
    } elseif (is_array($param)) {
      $emails = $param;
    }
    if (count($emails) > 1) {
      foreach ($emails as $email => $name) {
        fwrite($this->SMTP_Stream,"RCPT TO: <" . $email . '>' . self::EOL);
        $code = self::SMTP_GetResponse(['250','251'], 'RCPT TO');
      }
    } else {
      fwrite($this->SMTP_Stream,"RCPT TO: " . self::AddrFormatRFC2822($emails) . self::EOL);
      $code = self::SMTP_GetResponse(['250','251'], 'RCPT TO');
    }
  }

  /* Send RSET (aborts any transport in progress and keeps connection alive)
   * Implements RFC 821: RSET <EOL>
   * return code 250 success
   * @return bool
   */
  public function SMTP_Reset() {
    if (!self::SMTP_IsStreamConnected()) {
      exit(__LINE__ . ' ' . PHPMailer2::FAILMK . self::Lang('keep_alive_error') . '<br>' . self::EOL);
    }
    fwrite($this->SMTP_Stream,"RSET" . self::EOL);
    $code = self::SMTP_GetResponse(['250'], 'RSET');
    return true;
  }

  /**
   * Takes either a host or path (string) and returns the MX record domain name
   * @return string (mail server)
   */
  private function GetMailServer($url='') {
    if ($url == '') { $url = $_SERVER['SERVER_NAME']; }
    $bits = parse_url($url);
    if (isset($bits['host'])) { $key = 'host'; } elseif (isset($bits['path'])) { $key = 'path'; }
    $tld = $bits[$key];
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $tld, $match)) {
      getmxrr($match['domain'],$mx_details);
      if (is_array($mx_details) && count($mx_details)>0) {
        return reset($mx_details);
      }
    }
    return $url;
  }
  /* END - SMTP METHODS ************/
}

/* PHPMailer2 part of PHP Exception error handling (note, namespace makes Exception unique) */
class Exception extends \Exception {
  public function errorMessage() {
    $file  = $this->getFile();
    $line  = $this->getLine();
    $msg   = $this->getMessage();
    $trace = $this->getTrace(); // array
    $errorMsg  = 'Error at #' . $line;
    //$errorMsg .= ' in ' . $file;
    $errorMsg .= ': <b>' . htmlentities($msg) . '</b>';
    $errorMsg .= "<br>\n";

    $errorMsg  = '<div style="position:relative;font-family:Arial;color:#000;font-size:16px;border-radius:5px;border:4px #d83526 solid;background-color:#fff;cursor:pointer;display:inline-flex;align-items:center;">';
    $errorMsg .= '<span style="line-height:50px;display:flex;justify-content:center;position:absolute;top:0;left:0;bottom:0;display:inline-flex;align-items:center;width:50px;background:linear-gradient(180deg,#fe1900 5%,#ce0000 100%);text-shadow:1px 1px 1px #b23d35;border-right:1px solid rgba(255,255,255,.16);font-size:36px;color:#fff;-webkit-text-stroke:4.5px #000;paint-order:stroke fill;height:100%;">&#10008;</span>';
    $errorMsg .= '<span style="padding: 10px 10px 10px 60px;">';
    //$errorMsg .= '<b>' . htmlentities($msg) . '</b>';
    $errorMsg .= htmlentities($msg);
    $errorMsg .= '</span></div>';
    $errorMsg .= "<br>\n";

    return $errorMsg;
  }
}
?>