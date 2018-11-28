<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\AppUser;
use App\Entity\Setting;

class SettingManager
{

    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    public function createSettings($user)
    {
        $setting = new Setting();
        // needs to create new user

        $settingOptions = array(
            "accounting" => array(
                'label' => 'Accounting',
                'description' => 'When accounting and bookkeeping transactions need your attention.',
                'is_active' => true
            ),
            "sales" => array(
                'label' => 'Sales',
                'description' => 'When relevant sales-related activity occurs such as when an invoice is overdue.',
                'is_active' => true
            ),
            "payroll" => array(
                'label' => 'Payroll',
                'description' => 'When you need to be reminded of upcoming and/or late payrolls.',
                'is_active' => true
            ),
            "payments" => array(
                'label' => 'Payments',
                'description' => 'When you’ve been paid or need to be notified to keep your Payments by Wave operating.',
                'is_active' => true
            ),
            "purchases" => array(
                'label' => 'Purchases',
                'description' => 'When receipt exports are ready and when receipts you’ve emailed to Wave need to be posted into accounting.',
                'is_active' => true
            ),
            "banking" => array(
                'label' => 'Banking',
                'description' => 'When there are any issues related to your bank connections.',
                'is_active' => true
            ),
        );
        $setting->setUser($user);
        $setting->setData($settingOptions);

        $this->entityManager->persist($setting);
        $this->entityManager->flush();

        return array(
            'code' => Response::HTTP_OK,
            'message' => sprintf("A user settings created successfully")
        );
    }


    public function updateSettings($request,$user)
    {
        $setting = $this->entityManager->getRepository(Setting::class)->findOneBy(['user' => $user]);
        $setting->setData($request->all());
        $this->entityManager->persist($setting);
        $this->entityManager->flush();

        return array(
            'code' => Response::HTTP_OK,
            'message' => sprintf("A user settings saved successfully")
        );
    }

    public function getSetting($user){
        $conn = $this->entityManager->getConnection();
        $sql = sprintf('SELECT data FROM setting s WHERE s.user_id = %d',$user->getId());
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $response = $stmt->fetchAll();

        return $response[0]['data'];
    }

}




























































































































































































































































































































