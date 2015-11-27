<?php
// src/VersionContol/GitControlBundle/Utility/GitCommands/GitSyncCommand.php
namespace VersionContol\GitControlBundle\Utility\GitCommands;

use VersionContol\GitControlBundle\Utility\GitCommands\GitCommand;

/**
 * Git uses a collaboration modal,which gives every developer their own copy of the repository, complete with its own 
 * local history and branch structure. Users typically need to share a series of commits rather than a single changeset. 
 * Instead of committing a changeset from a working copy to the central repository, Git lets you share entire branches 
 * between repositories.
 * 
 * The commands  below let you manage connections with other repositories, 
 * publish local history by “pushing” branches to other repositories, 
 * and see what others have contributed by “pulling” branches into your local repository.
 * Commands: 
 *      remote 
 *      fetch 
 *      push
 *      pull
 * 
 */
class GitSyncCommand extends GitCommand {
    
    protected $pullRebase = false;
    
    /**
     * List the remote connections you have to other repositories.
     * 
     * $ git remote
     * origin
     * 
     * @return array() Array of remote names
     */
    public function getRemotes(){
        
        $remotes = $this->splitOnNewLine($this->runCommand('git remote'));
        return $remotes;
    }
    
    /**
     * List the remote connections you have to other repositories with "URL"
     * $ git remote -v
     * origin	https://github.com/schacon/ticgit (fetch)
     * origin	https://github.com/schacon/ticgit (push)
     * pb	https://github.com/paulboone/ticgit (fetch)
     * pb	https://github.com/paulboone/ticgit (push)
     * 
     * @return array eg (array(0 => "origin", 1 => "https://github.com/schacon/ticgit", 2 => "(push)")
     */
    public function getRemoteVersions(){
        $remotes = array();
        
        $lines = $this->splitOnNewLine($this->runCommand('git remote -v'));

        if(count($lines) >= 2){
            for($i = 1; $i < count($lines); $i+=2){
                $parts = preg_split('/\s+/', $lines[$i]);
                if($parts[2] == "(push)"){
                    $remotes[] = $parts;
                }
            }
        }
        return $remotes;
        
    }
    
    /**
     * Create a new connection to a remote repository. After adding a remote, you’ll be able to use 
     * $remote as a convenient shortcut for $url in other Git commands.
     * 
     * It’s generally not possible to push commits to a HTTP address. 
     * For read-write access, you should use SSH instead.
     * 
     * @param string $remote
     * @param string $url
     * @return string
     */
    public function addRemote($remote,$url){
        
        $remotes = $this->runCommand(sprintf('git remote add %s %s 2>&1',escapeshellarg($remote),escapeshellarg($url)));
        return $remotes;
    }
    
    /**
     * Remove the connection to the remote repository called $remote.
     * 
     * @param string $remote
     * @return string
     */
    public function deleteRemote($remote){
        
        $remotes = $this->runCommand(sprintf('git remote rm %s 2>&1',escapeshellarg($remote)));
        
        return $remotes;
    }
    
    /**
     * Remove the connection to the remote repository called $remote.
     * 
     * @param string $remote
     * @return string
     */
    public function renameRemote($remote,$newRemote){
        
        $remotes = $this->runCommand(sprintf('git remote rename %s %s 2>&1',escapeshellarg($remote),escapeshellarg($newRemote)));
        
        return $remotes;
    }
    
    /**
     * Fetch all of the branches from the repository. 
     * 
     * @param remote $remote Name of remote Repository
     * @return string command response
     */
    public function fetchAll($remote){

        return $this->runCommand(sprintf('git fetch %s 2>&1',escapeshellarg($remote)));
    }
    
    /**
     * Fetch changes from the remote server
     * 
     * @param remote $remote Name of remote Repository
     * @param string $branch Branch to fetch
     * @return string command response
     */
    public function fetch($remote,$branch){
        //return $this->runCommand(sprintf('git pull %s %s "2>&1"',escapeshellarg($remote),escapeshellarg($branch)));
        return $this->runCommand(sprintf('git fetch %s %s 2>&1',escapeshellarg($remote),escapeshellarg($branch)));
    }
    
    /**
     * 
     * @return string command response
     */
    public function resetPullRequest(){
        return $this->runCommand('git reset --hard ORIG_HEAD');
    }
    
    /**
     * Push specified branch to the remote repository
     * 
     * @param remote $remote The remote server to push to eg origin
     * @param string $branch The branch to push to the remote server eg master
     * @return string command response
     */
    public function push($remote,$branch){
        
        $command = sprintf('git push %s %s 2>&1',escapeshellarg($remote),escapeshellarg($branch));
        return $this->runCommand($command);
    }
    
    /**
     * Push all of your local branches to the specified remote repository
     * 
     * @param remote $remote The remote server to push to eg origin
     * @return string command response
     */
    public function pushAll($remote){
        
        $command = sprintf('git push %s --all 2>&1',escapeshellarg($remote));
        return $this->runCommand($command);
    }
    
    /**
     * Tags are not automatically pushed when you push a branch or use the --all option. 
     * The --tags flag sends all of your local tags to the remote repository.
     * 
     * @param remote $remote The remote server to push to eg origin
     * @return string command response
     */
    public function pushTags($remote){
        
        $command = sprintf('git push %s --tags 2>&1',escapeshellarg($remote));
        return $this->runCommand($command);
    }
    
    /**
     * Pull changes to the remote repository
     * 
     * @param remote $remote The remote server to push to eg origin
     * @param string $branch The branch to push to the remote server eg master
     * @return string command response
     */
    public function pull($remote,$branch){
        $command = 'git pull';
        if($this->pullRebase){
            $command .= ' --rebase';
        }
        
        $command = sprintf($command.' %s %s 2>&1',escapeshellarg($remote),escapeshellarg($branch));
        return $this->runCommand($command);
    }
    
}
