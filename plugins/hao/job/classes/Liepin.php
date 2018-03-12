<?php
/**
 * Created by PhpStorm.
 * User: Hao
 * Date: 2018/3/11
 * Time: 11:02
 */

namespace Hao\Job\Classes;
use Hao\Job\Classes\Liepin\Enterprise as HaoEnterprise;
use Hao\Job\Classes\Liepin\Qualification as HaoQualification;
use Hao\Job\Classes\Liepin\Tag as HaoTag;
use Hao\Job\Classes\Liepin\Jobdetail as HaoJob;
use Hao\Job\Classes\Liepin\Profil as HaoProfil;
use Hao\Job\Classes\Liepin\Department as HaoDep;
use Hao\Job\Classes\Liepin\Filiere as HaoFiliere;
use Hao\Job\Classes\Liepin\Responsable as HaoRes;
use Hao\Job\Classes\Liepin\Man as HaoMan;
use Hao\Job\Classes\Liepin\Salary as HaoSalary;
use Hao\Job\Classes\Liepin\Location as HaoLocation;
use Hao\Job\Classes\Liepin\Response as HaoResponse;

/**
 * Class Liepin
 * @package Hao\Job\Classes
 */
class Liepin extends Offer
{
    private $titleInfo = null;

    const patternTitleInfo = '/<div class="title-info([\s\S]+?)<\/div>/';
    const patternTitle = '<h1 title="([^"]+)">';
    const patternEnterprise = '/<h3>([\s\S]+?)<\/h3>/';
    const patternSalary ='/<p class="job-item-title">([\s\S]+?)<em>/';
    const patternResponse = '/title="反馈时间以工作日为准，周末和假期时间不会计算在内">([\s\S]+?)<\/span>/';

    const patternLocationInfo = '/<i class="icons24 icons24-position"><\/i>([\s\S]+?)<\/span>/';
    const patternLocation = '/>([\s\S]+?)</';

    const patternQualificationInfo = '/<div class="job-qualifications">([\s\S]+?)<\/div>/';
    const patternQualification = '/<span>([\s\S]+?)<\/span>/';

    const patternTagsInfo = '/<div class="tag-list">([\s\S]+?)<\/div>/';
    const patternTags = '/title="([\s\S]+?)"/';

    const patternJobDescriptionInfo = '/岗位职责：([\s\S]+?)<br\/><br\/>/';
    const patternJobDescription = '/<br\/>([^<]+)/';

    const patternProfileInfo = '/任职资格：([\s\S]+?)<\/div>/';
    const patternProfile = '/<br\/>([^<]+)/';

    const patternDepartment = '/所属部门：<\/span><label>([^<]+)<\/label><\/li>/';
    const patternfiliere = '/专业要求：<\/span><label>([^<]+)<\/label><\/li>/';
    const patternResponsable = '/汇报对象：<\/span><label>([^<]+)<\/label><\/li>/';
    const patternMen = '/下属人数：<\/span><label>([^<]+)<\/label><\/li>/';
    const patternEnterpriseDescription = '/<div class="info-word">([\s\S]+?)<\/div>/';

    const patternOfferId = '/\/([0-9]+)\.shtml/';

    public function saveLiepinOffer(){

        $titleInfor = $this->getTitleInfo();
        $title      = $this->getTitle();
        $enterprise = $this->getEnterprise();
        $salary     = $this->getSalary();
        $response   = $this->getInformation($this::patternResponse);

        $location = $this->getLocation();
        $qualification = $this->getQualification();
        $tag = $this->getTag();
        $jobDescription = $this->getJobDescription();
        $profile = $this->getProfile();
        $department = $this->getDepartment();
        $filire = $this->getFiliere();
        $responsable = $this->getResponsable();
        $men = $this->getMen();

        $enterpriseDescription = $this->getEnterpriseDescription();


        traceLog($title);

        // Offer Id
        $id = $this->getOfferId();

        // save the information about the enterprise
        $enterprise = new HaoEnterprise((string)$enterprise,
            (string)$enterpriseDescription);
        $enterprise = $enterprise->saveEnterprise();

        // save the qualifications' informations
        $qualification = new HaoQualification($qualification, $id);
        $qualification ->saveQualification();

        // save the job descriptions' informations
        $job = new HaoJob($jobDescription, $id);
        $job->saveJobdetail();

        // save the profils' informations
        $profile = new HaoProfil($profile, $id);
        $profile ->saveProfil();

        // save the tags' information
        $tag = new HaoTag($tag, $id);
        $tag ->saveTag();

        // save the department information
        $department = new HaoDep($department);
        $departmentId = $department->saveDepartment();

        // save the filiere information
        $filire = new HaoFiliere($filire);
        $filireId = $filire->save();

        // save the responsable information
        $responsable = new HaoRes($responsable);
        $responsableId = $responsable->save();

        // save the men's information
        $men = new HaoMan($men);
        $menId = $men->save();

        // save the salaries' information
        $salary = new HaoSalary($salary);
        $salaryId = $salary->save();

        // save the salaries' information
        $location = new HaoLocation($location);
        $locationId  = $location->save();

        // save the salaries' information
        $response = new HaoResponse($response);
        $responseId  = $response->save();

        traceLog($id);
        traceLog($enterprise);

        traceLog($qualification);
        traceLog($tag);
        traceLog($job);
        traceLog($profile);

        traceLog($departmentId);
        traceLog($filireId);
        traceLog($responsableId);
        traceLog($menId);

        traceLog($salaryId);
        traceLog($locationId);
        traceLog($responseId);
    }


    /**
     * @return mixed
     */
    private function getTitleInfo(){
        $this->titleInfo = $this->getInformation($this::patternTitleInfo);
        return $this->titleInfo;
    }

    /**
     * @return mixed
     */
    private function getTitle(){
        return $this->getInformationFromContent($this::patternTitle, $this->titleInfo);
    }


    /**
     * @return mixed
     */
    private function getEnterprise(){
        return $this->getInformationFromContent($this::patternEnterprise, $this->titleInfo);
    }


    /**
     * @return mixed
     */
    private function getSalary(){
        return $this->getInformation($this::patternSalary);
    }


    /**
     * @return mixed|null
     */
    private function getLocation(){
        $location = $this->getInformation($this::patternLocationInfo);
        if($location != null)
            return $this->getInformationFromContent($this::patternLocation, $location);
        else
            return null;
    }


    /**
     * @return mixed|null
     */
    private function getQualification(){
        $qualification = $this->getInformation($this::patternQualificationInfo);
        if($qualification != null){
            $result = $this->getAllInformationFromContent($this::patternQualification, $qualification);
        }
        return $result;
    }


    /**
     * @return mixed
     */
    private function getTag(){
        $tags = $this->getInformation($this::patternTagsInfo);
        if($tags != null){
            $result = $this->getAllInformationFromContent($this::patternTags, $tags);
        }
        return $result;
    }


    /**
     * @return array
     */
    private function getJobDescription(){
        $descriptions = $this->getInformation($this::patternJobDescriptionInfo);
        $descriptions = $this->getAllInformationFromContent($this::patternJobDescription,
            $descriptions);

        $result = array();

        foreach ($descriptions as $description){
            array_push($result, trim($description));
        }

        return $result;
    }


    /**
     * @return array
     */
    private function getProfile()
    {
        $profiles = $this->getInformation($this::patternProfileInfo);
        if ($profiles != null) {
            $profiles = $this->getAllInformationFromContent($this::patternProfile,
                $profiles);

            $result = array();

            foreach ($profiles as $profile) {
                array_push($result, trim($profile));
            }
            return $result;
        }
        else
            return null;
    }


    /**
     * @return mixed
     */
    private function getDepartment(){
        return $this->getInformation($this::patternDepartment);
    }


    /**
     * @return mixed
     */
    private function getFiliere(){
        return $this->getInformation($this::patternfiliere);
    }

    /**
     * @return mixed
     */
    private function getResponsable(){
        return $this->getInformation($this::patternResponsable);
    }

    /**
     * @return mixed
     */
    private function getMen(){
        return $this->getInformation($this::patternMen);
    }

    /**
     * @return mixed
     */
    private function getEnterpriseDescription(){
        $result = $this->getInformation($this::patternEnterpriseDescription);
        $result= str_replace('<br/>', '', $result);
        $result= str_replace('&nbsp;', '', $result);
        return $result;
    }


    /**
     * @return mixed
     */
    private function getOfferId(){
        return $this->getInformation($this::patternOfferId);
    }

}
