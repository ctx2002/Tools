set-location C:\Users\user\www\homestead
$Shell = $Host.UI.RawUI
$Shell.WindowTitle="Vagrant"
Clear-Host


Function TimedPrompt($prompt,$secondsToWait){   
    Write-Host -NoNewline $prompt
    $secondsCounter = 0
    $subCounter = 0
   
    While ( ($count -lt $secondsToWait) ){
        start-sleep -m 10
       
        if($Host.UI.RawUI.KeyAvailable){
            #You could check to see if it was the "Shift Key or another"
            $key = $host.UI.RawUI.ReadKey("NoEcho,IncludeKeyUp")
            break
        }
        else{
            $subCounter = $subCounter + 10
            if($subCounter -eq 1000)
            {
                $secondsCounter++
                $subCounter = 0
                Write-Host -NoNewline "."
            }       
            If ($secondsCounter -eq $secondsToWait) {
                Write-Host "`r`n"
                return $false;
            }
        }
    }
    Write-Host "`r`n"
    return $key;
}

$val = TimedPrompt "Running vagrant? (y/n)" 10
#Write-Host $val
if ($val.Character -eq 'y') {
    vagrant up
    C:\Windows\putty.exe -load "laravel"  
}