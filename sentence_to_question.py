# original source: https://github.com/JacobHA/Sentence-to-Question/

import nltk
from nltk.stem.wordnet import WordNetLemmatizer
from nltk.tag import pos_tag

def s_2_q(sentence):
    #sentence = input("Enter an English sentence to be transformed into a question: \n")
    sentence = sentence.lower()
    if sentence[-1] == '.':
        # remove the final period
        sentence = sentence[:-1]

    # makes sure sentence isn't in question form
    if '?' in sentence:
        print("Please do not input questions. Exiting...")
        return "ERROR"

    question_keywords = ['is', 'will', 'are', 'was', 'were', 'am']

    # split on space to pair each word with an index
    word_decomposition = sentence.split(' ')
    print(word_decomposition)

    # think about changing to pos_tag_sents - it is more efficient for a multi-sentence paragraph
    try:
        verbs_and_tags = [(i, tuple_i) for i, tuple_i in enumerate(pos_tag(word_decomposition)) if tuple_i[1][0]=='V']
    except:
        nltk.download('tag')
        verbs_and_tags = [(i, tuple_i) for i, tuple_i in enumerate(pos_tag(word_decomposition)) if tuple_i[1][0] == 'V']
    verbs = [word[1][0] for word in verbs_and_tags]
    tags = [word[1][1] for word in verbs_and_tags]

    # make sure sentence is not ordered like a question (with a verb in the first position)
    if word_decomposition[0] in verbs:
        print("Sentence is interrogative. Please only use imperative syntax. Exiting...")
        return "ERROR"
    print(verbs_and_tags)
    print(verbs)

    changed = False
    for i, word_i in enumerate(word_decomposition):
        if word_i in question_keywords:
            for q_word in question_keywords:

                word_decomposition.insert(0, word_i)
                word_decomposition.pop(i + 1)
                changed = True
                break


    # Check if unchanged
    if not changed:
        try:
            lemmatized_verbs = [WordNetLemmatizer().lemmatize(verb, 'v') for verb in verbs]
        except:
            nltk.download('wordnet')
            lemmatized_verbs = [WordNetLemmatizer().lemmatize(verb, 'v') for verb in verbs]
        lemmatized_verb = lemmatized_verbs[0]
        verb_loc = verbs_and_tags[0][0]
        print(verb_loc)
        word_decomposition[verb_loc] = lemmatized_verb

        # Verb reduction:
        if all([tag in ['VBN', 'VBD'] for tag in tags]):
            # past participle or past tense, resp.
            word_decomposition.insert(0, 'Did') # + word_decomposition

        elif all([tag in ['VBG','VBZ'] for tag in tags]):
            # present participle or present tense, resp.
            word_decomposition.insert(0, 'Does')

        elif all([tag in ['VBC', 'VBF'] for tag in tags]):
            # future
            word_decomposition.insert(0, 'Will')

        else:  #'VBP',
            # other verbs
            word_decomposition.insert(0, 'Do')
            print(f'possible error: verb not recognized, examine tag list:\n{tags}')


    # Post-processing:
    for i, word_i in enumerate(word_decomposition): # Cycle through all words

        if 'NP' in pos_tag([word_i])[0][1]: # Searches for a proper noun
            word_decomposition[i] = word_i.capitalize() # Capitalizes the proper noun

    word_decomposition[0] = word_decomposition[0].capitalize() # Capitalizes first word of sentence
    question = (' ').join(word_decomposition) # Create a string to reform the question instead of word by word
    question += '?'

    print(question) # Return the final formed question version of the input sentence
    return question
