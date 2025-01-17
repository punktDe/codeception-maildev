<?php
namespace PunktDe\Codeception\MailDev\ActorTraits;

/*
 * This file is part of the PunktDe\Codeception-MailDev package.
 *
 * This package is open source software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

trait MailDev
{
    /**
     * @Then /^the inbox contains (\d+) mails?$/
     * @param int|string $numberOfMails
     */
    public function theInboxContainsMails(int|string $numberOfMails): void
    {
        $this->inboxContainsNumberOfMails((int)$numberOfMails);
    }

    /**
     * @When I follow :link in the email
     * @param string $link
     */
    public function iFollowInTheEmailSentTo(string $link): void
    {
        $this->openMailByNumber(1);
        $this->followLinkInTheEmail($link);
    }

    /**
     * @When I clear my inbox
     */
    public function iClearMyInbox(): void
    {
        $this->clearInbox();
    }

    /**
     * @When I open the first mail
     */
    public function iOpenTheFirstMail(): void
    {
        $this->openMailByNumber(1);
    }

    /**
     * @When I open the second mail
     */
    public function iOpenTheSecondMail(): void
    {
        $this->openMailByNumber(2);
    }

    /**
     * @Then I should see :text in the email
     * @param string $text
     */
    public function iSeeInMail(string $text): void
    {
        $this->seeTextInMail($text);
    }

    /**
     * @Then The email is addressed to :address
     * @param string $address
     */
    public function mailIsAddressedTo(string $address): void
    {
        $this->checkRecipientAddress($address);
    }

    /**
     * @Then I should see :subject in the email subject
     * @param string $subject
     */
    public function iSeeSubjectOfMail(string $subject): void
    {
        $this->seeSubjectOfMail($subject);
    }

    /**
     * @Then This mail is spam
     */
    public function ifSpamMail(): void
    {
        $this->checkIfSpam();
    }
}
