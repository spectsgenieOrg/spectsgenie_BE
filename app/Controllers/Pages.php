<?php

namespace App\Controllers;

use App\Models\PagesModel;


class Pages extends BaseController
{

    public function getpage($pagetype)
    {
        $db = db_connect();

        $pageModel = new PagesModel($db);

        $meta = (object) array('title' => '', 'type' => '');

        if ($pagetype === "privacy") {
            $meta->{'title'} = "Privacy Policy";
            $meta->{'type'} = 'privacypolicy';
        } else if ($pagetype === "shipping") {
            $meta->{'title'} = "Shipping Policy";
            $meta->{'type'} = 'shippingpolicy';
        } else if ($pagetype === "refund") {
            $meta->{'title'} = "Refund Policy";
            $meta->{'type'} = 'refundpolicy';
        } else if ($pagetype === "disclaimer") {
            $meta->{'title'} = "Disclaimer";
            $meta->{'type'} = 'disclaimer';
        } else if ($pagetype === "legal") {
            $meta->{'title'} = "Legal Policy";
            $meta->{'type'} = 'legalpolicy';
        } else if ($pagetype === "terms") {
            $meta->{'title'} = "Terms and Conditions";
            $meta->{'type'} = 'terms';
        } else if ($pagetype === "ipd") {
            $meta->{'title'} = "How to measure IPD";
            $meta->{'type'} = 'ipd';
        } else if ($pagetype === "contactlensusage") {
            $meta->{'title'} = "Contact Lenses Usage";
            $meta->{'type'} = 'contactlensusage';
        }

        $pagecontent = $pageModel->getPageContentByType($meta->{'type'});

        $data['pagecontent'] = $pagecontent;

        $data['metadata'] = $meta;

        return view('common/header')
            . view('pages/static-page', $data)
            . view('common/footer');
    }

    public function shipping()
    {
        $db = db_connect();

        $pageModel = new PagesModel($db);
        $shippingpolicy = $pageModel->getPageContentByType('shippingpolicy');

        $data['shippingpolicy'] = $shippingpolicy;
        return view('common/header')
            . view('pages/shipping-policy', $data)
            . view('common/footer');
    }

    // API List

    public function addcontent()
    {
        $db = db_connect();

        $pagesModel = new PagesModel($db);

        $post = json_decode($this->request->getBody());

        unset($post->{'files'});

        $isPageAdded = $pagesModel->addPageContent($post);

        $response = array("status" => $isPageAdded, "message" => $isPageAdded ? "Static content added successfully" : "Error occurred while creating");
        echo json_encode($response);
    }

    public function updatecontent()
    {
        $db = db_connect();

        $pagesModel = new PagesModel($db);

        $post = json_decode($this->request->getBody());

        unset($post->{'files'});

        $type = $post->type;
        unset($post->{'type'});

        $isPageupdated = $pagesModel->updateContent($post, $type);

        $response = array("status" => true, "message" => "Static content updated successfully");
        echo json_encode($response);
    }




    /**
     * $type values
     */
    public function content($type)
    {
        $db = db_connect();

        $pagesModel = new PagesModel($db);

        $data = $pagesModel->getPageContentByType($type);
        if ($data) {
            $response = array("status" => true, "message" => "Content available", "data" => $data);
        } else {
            $response = array("status" => false, "message" => "Content not available");
        }

        echo json_encode($response);
    }
}
