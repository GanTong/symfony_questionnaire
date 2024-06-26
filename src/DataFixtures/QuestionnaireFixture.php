<?php

namespace App\DataFixtures;

use App\Service\ProductService;
use App\Service\SeederService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuestionnaireFixture extends Fixture
{

    /**
     * @var SeederService
     */
    protected $seedService;

    public function __construct(SeederService $seedService)
    {
        $this->seedService = $seedService;
    }

    /**
     * To seed a new version, questions and question options
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $array1 = [
            ['name' => 'first_version_new']
        ];

        $this->seedService->seedEntity('versions', $array1);

        $array2 = [
            ['question_identifier' => '1', 'question_label' => 'Do you have difficulty getting or maintaining an erection?', 'is_multichoice' => '1', 'version_id' => 1],
            ['question_identifier' => '2', 'question_label' => 'Have you tried any of the following treatments before?', 'is_multichoice' => '1', 'version_id' => 1],
            ['question_identifier' => '2a', 'question_label' => 'Was the Viagra or Sildenafil product you tried before effective?', 'is_multichoice' => '1', 'version_id' => 1],
            ['question_identifier' => '2b', 'question_label' => 'Was the Cialis or Tadalafil product you tried before effective?', 'is_multichoice' => '1', 'version_id' => 1],
            ['question_identifier' => '2c', 'question_label' => 'Which is your preferred treatment?', 'is_multichoice' => '1', 'version_id' => 1],
            ['question_identifier' => '3', 'question_label' => 'Do you have, or have you ever had, any heart or neurological conditions?', 'is_multichoice' => '1', 'version_id' => 1],
            ['question_identifier' => '4', 'question_label' => 'Do any of the listed medical conditions apply to you?', 'is_multichoice' => '1', 'version_id' => 1],
            ['question_identifier' => '5', 'question_label' => 'Are you taking any of the following drugs?', 'is_multichoice' => '1', 'version_id' => 1]
        ];

        $this->seedService->seedEntity('questions', $array2);

        $array3 = [
            ['question_identifier' => '1', 'question_option_label' => 'Yes', 'question_choice_number' => 1],
            ['question_identifier' => '1', 'question_option_label' => 'No', 'question_choice_number' => 2],
            ['question_identifier' => '2', 'question_option_label' => 'Viagra or Sildenafil', 'question_choice_number' => 1],
            ['question_identifier' => '2', 'question_option_label' => 'Cialis or Tadalafil', 'question_choice_number' => 2],
            ['question_identifier' => '2', 'question_option_label' => 'Both', 'question_choice_number' => 3],
            ['question_identifier' => '2', 'question_option_label' => 'None of the above', 'question_choice_number' => 4],
            ['question_identifier' => '2a', 'question_option_label' => 'Yes', 'question_choice_number' => 1],
            ['question_identifier' => '2a', 'question_option_label' => 'No', 'question_choice_number' => 2],
            ['question_identifier' => '2b', 'question_option_label' => 'Yes', 'question_choice_number' => 1],
            ['question_identifier' => '2b', 'question_option_label' => 'No', 'question_choice_number' => 2],
            ['question_identifier' => '2c', 'question_option_label' => 'Viagra or Sildenafil', 'question_choice_number' => 1],
            ['question_identifier' => '2c', 'question_option_label' => 'Cialis or Tadalafil', 'question_choice_number' => 2],
            ['question_identifier' => '2c', 'question_option_label' => 'None of the above', 'question_choice_number' => 3],
            ['question_identifier' => '3', 'question_option_label' => 'Yes', 'question_choice_number' => 1],
            ['question_identifier' => '3', 'question_option_label' => 'No', 'question_choice_number' => 2],
            ['question_identifier' => '4', 'question_option_label' => 'Significant liver problems (such as cirrhosis of the liver) or kidney problems', 'question_choice_number' => 1],
            ['question_identifier' => '4', 'question_option_label' => 'Currently prescribed GTN, Isosorbide mononitrate, Isosorbide dinitrate , Nicorandil (nitrates) or Rectogesic ointment', 'question_choice_number' => 2],
            ['question_identifier' => '4', 'question_option_label' => 'Abnormal blood pressure (lower than 90/50 mmHg or higher than 160/90 mmHg)', 'question_choice_number' => 3],
            ['question_identifier' => '4', 'question_option_label' => "Condition affecting your penis (such as Peyronie's Disease, previous injuries or an inability to retract your foreskin)", 'question_choice_number' => 4],
            ['question_identifier' => '4', 'question_option_label' => "I don't have any of these conditions", 'question_choice_number' => 5],
            ['question_identifier' => '5', 'question_option_label' => 'Alpha-blocker medication such as Alfuzosin, Doxazosin, Tamsulosin, Prazosin, Terazosin or over-the-counter Flomax', 'question_choice_number' => 1],
            ['question_identifier' => '5', 'question_option_label' => 'Riociguat or other guanylate cyclase stimulators (for lung problems)', 'question_choice_number' => 2],
            ['question_identifier' => '5', 'question_option_label' => 'Saquinavir, Ritonavir or Indinavir (for HIV)', 'question_choice_number' => 3],
            ['question_identifier' => '5', 'question_option_label' => 'Cimetidine (for heartburn)', 'question_choice_number' => 4],
            ['question_identifier' => '5', 'question_option_label' => "I don't take any of these drugs", 'question_choice_number' => 5]
        ];

        $this->seedService->seedEntity('questionOptions', $array3);

        $manager->flush();
    }
}
